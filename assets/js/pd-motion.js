/* ============================================================================
 * pd-motion.js — IntersectionObserver-based reveal driver + counter animator.
 *
 * Companion to pd-motion.css. Tiny (no deps), idempotent, prefers-reduced-motion
 * aware. Sets <html class="pd-motion-ready"> early so the CSS hidden-state only
 * applies when JS is alive (no FOUC for no-JS users).
 *
 * Behavior:
 *   - Any element with [data-reveal] gets observed. When 15% in view, it
 *     receives data-revealed="true" (one-shot — never reverts).
 *   - Any element with [data-count-to] is treated as a numeric counter. When
 *     it enters view, it animates from 0 → target value over 1100ms.
 *   - Any element with [data-pct-to="80"] sets --pd-pct to "80%" when revealed
 *     (drives .pd-counter-bar fill).
 *
 * Apply on every page via main_head_start() — keeping it under 1.5KB so it's
 * a single-packet asset.
 * ========================================================================== */
(function () {
  "use strict";

  // Tag <html> as motion-capable so the CSS's "hidden initial state" engages.
  // Done synchronously to avoid a flash where revealed-elements are visible
  // for one frame before being hidden.
  document.documentElement.classList.add("pd-motion-ready");

  // Honor reduced-motion: skip observers entirely; mark everything revealed.
  var reduced = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function markAllRevealed() {
    var nodes = document.querySelectorAll("[data-reveal]");
    for (var i = 0; i < nodes.length; i++) nodes[i].setAttribute("data-revealed", "true");
    var bars = document.querySelectorAll("[data-pct-to]");
    for (var j = 0; j < bars.length; j++) bars[j].style.setProperty("--pd-pct", bars[j].getAttribute("data-pct-to") + "%");
    var nums = document.querySelectorAll("[data-count-to]");
    for (var k = 0; k < nums.length; k++) nums[k].textContent = nums[k].getAttribute("data-count-to");
  }

  if (reduced || !("IntersectionObserver" in window)) {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", markAllRevealed);
    } else {
      markAllRevealed();
    }
    return;
  }

  function animateCounter(el) {
    var target = parseFloat(el.getAttribute("data-count-to")) || 0;
    var duration = parseInt(el.getAttribute("data-count-duration"), 10) || 1100;
    var suffix = el.getAttribute("data-count-suffix") || "";
    var decimals = parseInt(el.getAttribute("data-count-decimals"), 10) || 0;
    var start = performance.now();

    function tick(now) {
      var elapsed = now - start;
      var t = Math.min(1, elapsed / duration);
      // ease-out-expo
      var eased = t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
      var v = target * eased;
      el.textContent = (decimals ? v.toFixed(decimals) : Math.round(v).toLocaleString()) + suffix;
      if (t < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
  }

  function fillBar(el) {
    var pct = el.getAttribute("data-pct-to");
    if (pct != null) el.style.setProperty("--pd-pct", pct + "%");
  }

  var observer = new IntersectionObserver(function (entries) {
    for (var i = 0; i < entries.length; i++) {
      var e = entries[i];
      if (!e.isIntersecting) continue;
      var el = e.target;
      el.setAttribute("data-revealed", "true");
      if (el.hasAttribute("data-count-to")) animateCounter(el);
      if (el.hasAttribute("data-pct-to")) fillBar(el);
      observer.unobserve(el);
    }
  }, {
    rootMargin: "0px 0px -10% 0px",
    threshold: 0.15
  });

  function bind() {
    var nodes = document.querySelectorAll("[data-reveal], [data-count-to], [data-pct-to]");
    for (var i = 0; i < nodes.length; i++) {
      // skip if already revealed (re-binding after dynamic content insert)
      if (nodes[i].getAttribute("data-revealed") === "true") continue;
      observer.observe(nodes[i]);
    }
  }

  // Re-scan API for code that injects DOM later (dashboards, modals, etc.)
  window.pdMotionRescan = bind;

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", bind);
  } else {
    bind();
  }
})();
