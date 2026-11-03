import axios from "axios";
import demo from "../data/demoApps.json";

const API_URL = process.env.REACT_APP_API_URL || "http://localhost:8084";

// REACT_APP_USE_DEMO_DATA=true skips the network entirely (handy for static hosting).
// Otherwise the bundled demo data is used automatically when the API cannot be reached.
let demoMode = process.env.REACT_APP_USE_DEMO_DATA === "true";

export const isDemoMode = () => demoMode;

const handleError = (error) => error.response?.data?.message || error.message || "Terjadi kesalahan yang tidak diketahui";

// Try the API; if there is no response at all (offline, refused, timeout), serve the bundled data instead.
const withFallback = async (remote, local) => {
  if (demoMode) return local();
  try {
    return await remote();
  } catch (error) {
    if (!error.response) {
      demoMode = true;
      return local();
    }
    throw new Error(handleError(error));
  }
};

const get = (path, params) =>
  axios.get(`${API_URL}${path}`, { params, headers: { "Content-Type": "application/json" }, timeout: 8000 }).then((r) => r.data);

const withLogo = ({ category_ids, ...app }) => app;
const sorted = (apps) => [...apps].sort((a, b) => a.app_order - b.app_order);

export const getCards = () =>
  withFallback(
    () => get("/api/apps/getAll"),
    () => sorted(demo.apps).map(withLogo)
  );

export const searchCards = async (searchParams) => {
  if (!searchParams || Object.keys(searchParams).length === 0) {
    throw new Error("Search parameters are missing");
  }
  return withFallback(
    () => get("/api/apps/getBySearch", searchParams),
    () => {
      const name = (searchParams.app_name || "").toLowerCase();
      const desc = (searchParams.app_desc || "").toLowerCase();
      const rows = sorted(demo.apps).filter(
        (a) => (!name || a.app_name.toLowerCase().includes(name)) && (!desc || a.app_desc.toLowerCase().includes(desc))
      );
      return { success: true, formattedData: rows.map(withLogo) };
    }
  );
};

export const getWebsiteTypes = () =>
  withFallback(
    () => get("/api/apps/website-types"),
    () => demo.websiteTypes
  );

export const getBusinessCategories = () =>
  withFallback(
    () => get("/api/apps/business-categories"),
    () => demo.businessCategories
  );

export const getFilteredCards = async (filters, searchTerm = "") => {
  const params = {};
  if (filters.websiteTypeId) params.website_type_id = filters.websiteTypeId;
  if (filters.businessCategoryId) params.business_category_id = filters.businessCategoryId;

  const rows = await withFallback(
    () => get("/api/apps/filter", params),
    () =>
      sorted(demo.apps)
        .filter((a) => !params.website_type_id || a.website_type_id === Number(params.website_type_id))
        .filter((a) => !params.business_category_id || a.category_ids.includes(Number(params.business_category_id)))
        .map(withLogo)
  );

  if (!searchTerm) return rows;
  const q = searchTerm.toLowerCase();
  return rows.filter((card) => (card.app_name || "").toLowerCase().includes(q) || (card.app_desc || "").toLowerCase().includes(q));
};
