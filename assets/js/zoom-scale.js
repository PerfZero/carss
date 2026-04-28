(function () {
  var DESIGN_WIDTH = 1920;
  var MOBILE_DESIGN_WIDTH = 390;
  var MOBILE_BREAKPOINT = 768;

  function scalePage() {
    var vw = window.innerWidth;
    if (vw > MOBILE_BREAKPOINT) {
      document.documentElement.style.zoom = vw / DESIGN_WIDTH;
    } else {
      document.documentElement.style.zoom = vw / MOBILE_DESIGN_WIDTH;
    }
  }

  scalePage();
  window.addEventListener("resize", scalePage);
})();
