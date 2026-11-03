// Smoke test: boots the real server on a throwaway SQLite file and calls it over HTTP.
const { test, before, after } = require("node:test");
const assert = require("node:assert/strict");
const { spawn } = require("node:child_process");
const path = require("node:path");
const fs = require("node:fs");
const os = require("node:os");

const PORT = 18084;
const BASE = `http://127.0.0.1:${PORT}`;
const storage = path.join(os.tmpdir(), `apphub-test-${process.pid}.sqlite`);
let server;

const json = (url, options) => fetch(BASE + url, options).then(async (r) => ({ status: r.status, body: await r.json().catch(() => null) }));
const post = (url, body) => json(url, { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(body) });

before(async () => {
  server = spawn(process.execPath, ["server.js"], {
    cwd: path.join(__dirname, ".."),
    env: { ...process.env, PORT, DB_STORAGE: storage, JWT_SECRET: "test-secret", CORS_ORIGINS: "http://localhost:3000" },
    stdio: "ignore",
  });
  for (let i = 0; i < 50; i++) {
    try { if ((await fetch(BASE + "/health")).ok) return; } catch { /* not up yet */ }
    await new Promise((r) => setTimeout(r, 200));
  }
  throw new Error("server did not start");
});

after(async () => {
  const exited = new Promise((resolve) => server.once("exit", resolve));
  server.kill();
  await exited; // Windows keeps the SQLite file locked until the process is gone
  fs.rmSync(storage, { force: true, maxRetries: 5, retryDelay: 100 });
});

test("catalogue is seeded and filterable", async () => {
  const all = await json("/api/apps/getAll");
  assert.equal(all.status, 200);
  assert.equal(all.body.length, 14);
  assert.match(all.body[0].app_logo, /^data:image\/svg\+xml;base64,/);

  const ops = await json("/api/apps/filter?business_category_id=3");
  assert.ok(ops.body.length > 0 && ops.body.every((a) => a.app_name));

  const narrow = await json("/api/apps/filter?website_type_id=3&business_category_id=3");
  assert.ok(narrow.body.length < ops.body.length);

  const found = await json("/api/apps/getBySearch?app_name=PROCURE");
  assert.equal(found.body.formattedData.length, 1, "search ignores case");
});

test("sign in works and gives a JWT; bad password does not", async () => {
  const ok = await post("/api/auth/signin", { username: "admin", password: "Admin#12345" });
  assert.equal(ok.status, 200);
  assert.ok(ok.body.accessToken.split(".").length === 3);
  assert.ok(ok.body.roles.includes("ROLE_ADMIN"));

  const bad = await post("/api/auth/signin", { username: "admin", password: "nope" });
  assert.equal(bad.status, 401);
  assert.equal(bad.body.accessToken, null);
});

test("sign up cannot grant roles", async () => {
  const res = await post("/api/auth/signup", { username: "mallory", email: "m@example.com", password: "password123", roles: ["admin"] });
  assert.equal(res.status, 200);
  const login = await post("/api/auth/signin", { username: "mallory", password: "password123" });
  assert.deepEqual(login.body.roles, ["ROLE_USER"]);

  const dup = await post("/api/auth/signup", { username: "mallory", email: "x@example.com", password: "password123" });
  assert.equal(dup.status, 400);
  const weak = await post("/api/auth/signup", { username: "weak", email: "w@example.com", password: "123" });
  assert.equal(weak.status, 400);
});

test("browsers from unknown origins are refused", async () => {
  const res = await fetch(BASE + "/api/apps/getAll", { headers: { Origin: "https://evil.example" } });
  assert.equal(res.status, 403);
  const allowed = await fetch(BASE + "/api/apps/getAll", { headers: { Origin: "http://localhost:3000" } });
  assert.equal(allowed.status, 200);
});
