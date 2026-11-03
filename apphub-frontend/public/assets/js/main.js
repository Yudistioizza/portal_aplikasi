/**
 * Page behaviour that lives outside React: scroll animations (AOS) and the sticky header shadow.
 * React renders after this script runs, so AOS is refreshed whenever the DOM under #root changes.
 */
(function () {
  "use strict";

  function toggleScrolled() {
    document.body.classList.toggle("scrolled", window.scrollY > 100);
  }
  window.addEventListener("scroll", toggleScrolled);
  window.addEventListener("load", toggleScrolled);

  function startAos() {
    if (typeof AOS === "undefined") return;
    AOS.init({ duration: 600, easing: "ease-in-out", once: true, mirror: false });

    var root = document.getElementById("root");
    if (!root || typeof MutationObserver === "undefined") return;
    var pending = null;
    new MutationObserver(function () {
      clearTimeout(pending);
      pending = setTimeout(function () { AOS.refreshHard(); }, 60);
    }).observe(root, { childList: true, subtree: true });
  }

  window.addEventListener("load", startAos);
})();
