(function () {
  function getGap(track) {
    var gap = window.getComputedStyle(track).columnGap;
    return parseFloat(gap) || 0;
  }

  function initSlider(root, config) {
    var viewport = root.querySelector(config.viewport);
    var track = root.querySelector(config.track);
    var slides = Array.prototype.slice.call(root.querySelectorAll(config.slide));
    var prev = root.querySelector(config.prev);
    var next = root.querySelector(config.next);

    if (!viewport || !track || slides.length === 0 || !prev || !next) {
      return;
    }

    var currentOffset = 0;
    var startX = 0;
    var dragX = 0;
    var isDragging = false;
    var wheelDelta = 0;
    var wheelTimer = null;

    function metrics() {
      var slideWidth = slides[0].offsetWidth;
      var gap = getGap(track);
      var step = slideWidth + gap;
      var maxOffset = Math.max(0, track.scrollWidth - viewport.clientWidth);

      return {
        step: step,
        maxOffset: maxOffset,
      };
    }

    function update(disableTransition) {
      var data = metrics();
      currentOffset = Math.max(0, Math.min(currentOffset, data.maxOffset));

      track.style.transition = disableTransition ? "none" : "";
      track.style.transform = "translate3d(" + -currentOffset + "px, 0, 0)";

      prev.disabled = currentOffset <= 0;
      next.disabled = currentOffset >= data.maxOffset;
    }

    function move(delta) {
      currentOffset += delta * metrics().step;
      update(false);
    }

    prev.addEventListener("click", function () {
      move(-1);
    });

    next.addEventListener("click", function () {
      move(1);
    });

    viewport.addEventListener(
      "wheel",
      function (event) {
        var data = metrics();
        var delta = Math.abs(event.deltaX) > Math.abs(event.deltaY) ? event.deltaX : event.deltaY;

        if (data.maxOffset === 0 || Math.abs(delta) < 2) {
          return;
        }

        if ((currentOffset <= 0 && delta < 0) || (currentOffset >= data.maxOffset && delta > 0)) {
          return;
        }

        event.preventDefault();
        wheelDelta += delta;

        if (Math.abs(wheelDelta) >= 48) {
          move(wheelDelta > 0 ? 1 : -1);
          wheelDelta = 0;
        }

        window.clearTimeout(wheelTimer);
        wheelTimer = window.setTimeout(function () {
          wheelDelta = 0;
        }, 140);
      },
      { passive: false },
    );

    viewport.addEventListener("pointerdown", function (event) {
      isDragging = true;
      startX = event.clientX;
      dragX = 0;
      viewport.setPointerCapture(event.pointerId);
      track.classList.add("is-dragging");
    });

    viewport.addEventListener("pointermove", function (event) {
      if (!isDragging) {
        return;
      }

      var data = metrics();
      dragX = event.clientX - startX;
      track.style.transition = "none";
      track.style.transform = "translate3d(" + (-currentOffset + dragX) + "px, 0, 0)";
    });

    function endDrag(event) {
      if (!isDragging) {
        return;
      }

      isDragging = false;
      track.classList.remove("is-dragging");

      if (Math.abs(dragX) > 50) {
        currentOffset += dragX < 0 ? metrics().step : -metrics().step;
        update(false);
      } else {
        update(false);
      }

      if (event.pointerId !== undefined && viewport.hasPointerCapture(event.pointerId)) {
        viewport.releasePointerCapture(event.pointerId);
      }
    }

    viewport.addEventListener("pointerup", endDrag);
    viewport.addEventListener("pointercancel", endDrag);
    window.addEventListener("resize", function () {
      update(true);
    });

    update(true);
  }

  function formatPhoneValue(value) {
    var digits = String(value || "").replace(/\D/g, "");

    if (digits.charAt(0) === "8") {
      digits = "7" + digits.slice(1);
    }

    if (digits.charAt(0) !== "7") {
      digits = "7" + digits;
    }

    digits = digits.slice(0, 11);

    var result = "+7";
    var code = digits.slice(1, 4);
    var first = digits.slice(4, 7);
    var second = digits.slice(7, 9);
    var third = digits.slice(9, 11);

    if (code) {
      result += " (" + code;
    }

    if (code.length === 3) {
      result += ")";
    }

    if (first) {
      result += " " + first;
    }

    if (second) {
      result += "-" + second;
    }

    if (third) {
      result += "-" + third;
    }

    return result;
  }

  function initPhoneMasks() {
    Array.prototype.forEach.call(document.querySelectorAll('input[type="tel"]'), function (input) {
      input.setAttribute("inputmode", "tel");
      input.setAttribute("maxlength", "18");

      function syncValue() {
        var digits = input.value.replace(/\D/g, "");

        if (!digits) {
          input.value = "";
          return;
        }

        input.value = formatPhoneValue(input.value);
      }

      input.addEventListener("focus", function () {
        if (!input.value) {
          input.value = "+7 (";
        }
      });

      input.addEventListener("input", syncValue);

      input.addEventListener("blur", function () {
        if (input.value.replace(/\D/g, "").length <= 1) {
          input.value = "";
        }
      });

      syncValue();
    });
  }

  function init() {
    var header = document.querySelector(".site-header");
    var headerToggle = header ? header.querySelector("[data-header-toggle]") : null;
    var headerPanel = header ? header.querySelector("[data-header-panel]") : null;
    var modal = document.querySelector('.cars-modal[data-modal="contact"]');

    function setHeaderMenuState(isOpen) {
      if (!header || !headerToggle || !headerPanel) {
        return;
      }

      header.classList.toggle("is-menu-open", isOpen);
      headerToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      headerToggle.setAttribute("aria-label", isOpen ? "Закрыть меню" : "Открыть меню");
    }

    if (header && headerToggle && headerPanel) {
      headerToggle.addEventListener("click", function () {
        setHeaderMenuState(!header.classList.contains("is-menu-open"));
      });

      document.addEventListener("click", function (event) {
        if (!header.classList.contains("is-menu-open")) {
          return;
        }

        if (header.contains(event.target)) {
          return;
        }

        setHeaderMenuState(false);
      });

      Array.prototype.forEach.call(headerPanel.querySelectorAll("a"), function (link) {
        link.addEventListener("click", function () {
          setHeaderMenuState(false);
        });
      });
    }

    function setModalState(isOpen) {
      if (!modal) {
        return;
      }

      modal.classList.toggle("is-open", isOpen);
      modal.setAttribute("aria-hidden", isOpen ? "false" : "true");
      document.documentElement.classList.toggle("cars-modal-open", isOpen);
      document.body.classList.toggle("cars-modal-open", isOpen);
    }

    if (modal) {
      Array.prototype.forEach.call(
        document.querySelectorAll('[data-open-modal="contact"]'),
        function (trigger) {
          trigger.addEventListener("click", function (event) {
            event.preventDefault();
            setModalState(true);

            window.setTimeout(function () {
              var field = modal.querySelector('input[name="name"], input[name="phone"]');

              if (field) {
                field.focus();
              }
            }, 120);
          });
        },
      );

      Array.prototype.forEach.call(
        modal.querySelectorAll('[data-modal-close="contact"]'),
        function (trigger) {
          trigger.addEventListener("click", function () {
            setModalState(false);
          });
        },
      );

      document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") {
          return;
        }

        if (header && header.classList.contains("is-menu-open")) {
          setHeaderMenuState(false);
        }

        if (modal.classList.contains("is-open")) {
          setModalState(false);
        }
      });

      if (modal.classList.contains("is-open")) {
        setModalState(true);
      }
    }

    initPhoneMasks();

    Array.prototype.forEach.call(document.querySelectorAll(".expert-reviews"), function (root) {
      initSlider(root, {
        viewport: ".expert-reviews__viewport",
        track: ".expert-reviews__list",
        slide: ".expert-review",
        prev: '.expert-reviews__nav button[aria-label="Предыдущий отзыв"]',
        next: '.expert-reviews__nav button[aria-label="Следующий отзыв"]',
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll(".team-showcase"), function (root) {
      initSlider(root, {
        viewport: ".team-showcase__viewport",
        track: ".team-showcase__list",
        slide: ".team-card",
        prev: '.team-showcase__nav button[aria-label="Предыдущий сотрудник"]',
        next: '.team-showcase__nav button[aria-label="Следующий сотрудник"]',
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll(".blog-preview"), function (root) {
      initSlider(root, {
        viewport: ".blog-preview__viewport",
        track: ".blog-preview__grid",
        slide: ".blog-card",
        prev: '.blog-preview__nav button[aria-label="Предыдущая статья"]',
        next: '.blog-preview__nav button[aria-label="Следующая статья"]',
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll(".faq-card"), function (card) {
      var button = card.querySelector("button");

      if (!button) {
        return;
      }

      button.addEventListener("click", function () {
        var isOpen = card.classList.toggle("is-open");
        button.setAttribute("aria-expanded", isOpen ? "true" : "false");
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll(".faq-section"), function (section) {
      var button = section.querySelector(".faq-section__button");

      if (!button) {
        return;
      }

      button.addEventListener("click", function () {
        var isExpanded = section.classList.toggle("is-expanded");
        button.textContent = isExpanded ? button.dataset.expandedText : button.dataset.collapsedText;
      });
    });

    Array.prototype.forEach.call(document.querySelectorAll("[data-quiz-form]"), function (quizForm) {
      var steps = Array.prototype.slice.call(quizForm.querySelectorAll("[data-quiz-step]"));
      var startStep = parseInt(quizForm.getAttribute("data-start-step") || "1", 10) - 1;
      var currentStep = Math.max(0, Math.min(startStep, steps.length - 1));

      if (!steps.length) {
        return;
      }

      function syncSelectedOptions(step) {
        Array.prototype.forEach.call(step.querySelectorAll(".expert-quiz__option"), function (option) {
          var input = option.querySelector("input");
          option.classList.toggle("is-selected", !!(input && input.checked));
        });
      }

      function isStepComplete(step) {
        if (step.classList.contains("expert-quiz__step--contact")) {
          var phoneField = step.querySelector('input[name="phone"]');
          var consentField = step.querySelector('input[name="consent"]');
          var phoneDigits = phoneField ? phoneField.value.replace(/\D/g, "").length : 0;

          return phoneDigits >= 10 && !!(consentField && consentField.checked);
        }

        return !!step.querySelector('input[type="radio"]:checked');
      }

      function updateStepControls(step) {
        var nextButton = step.querySelector("[data-quiz-next]");
        var submitButton = step.querySelector(".expert-quiz__action--submit");
        var complete = isStepComplete(step);

        syncSelectedOptions(step);

        if (nextButton) {
          nextButton.disabled = !complete;
        }

        if (submitButton) {
          submitButton.disabled = !complete;
        }
      }

      function showStep(index, shouldFocus) {
        currentStep = index;

        Array.prototype.forEach.call(steps, function (step, stepIndex) {
          var isActive = stepIndex === currentStep;

          step.hidden = !isActive;
          step.classList.toggle("is-active", isActive);
          step.setAttribute("aria-hidden", isActive ? "false" : "true");

          if (isActive) {
            updateStepControls(step);
          }
        });

        var activeStep = steps[currentStep];
        var focusField = activeStep.querySelector(
          '.expert-quiz__field input, input[type="radio"], [data-quiz-next], .expert-quiz__action--submit',
        );

        if (shouldFocus && focusField) {
          window.setTimeout(function () {
            focusField.focus();
          }, 80);
        }
      }

      Array.prototype.forEach.call(quizForm.querySelectorAll(".expert-quiz__option input"), function (input) {
        input.addEventListener("change", function () {
          var step = input.closest("[data-quiz-step]");

          if (!step) {
            return;
          }

          updateStepControls(step);
        });
      });

      Array.prototype.forEach.call(quizForm.querySelectorAll("[data-quiz-next]"), function (button) {
        button.addEventListener("click", function () {
          var step = button.closest("[data-quiz-step]");

          if (!step || !isStepComplete(step)) {
            return;
          }

          showStep(Math.min(currentStep + 1, steps.length - 1), true);
        });
      });

      Array.prototype.forEach.call(quizForm.querySelectorAll("[data-quiz-prev]"), function (button) {
        button.addEventListener("click", function () {
          showStep(Math.max(currentStep - 1, 0), true);
        });
      });

      Array.prototype.forEach.call(
        quizForm.querySelectorAll('.expert-quiz__step--contact input[name="phone"], .expert-quiz__step--contact input[name="consent"]'),
        function (field) {
          field.addEventListener("input", function () {
            var step = field.closest("[data-quiz-step]");

            if (step) {
              updateStepControls(step);
            }
          });

          field.addEventListener("change", function () {
            var step = field.closest("[data-quiz-step]");

            if (step) {
              updateStepControls(step);
            }
          });
        },
      );

      showStep(currentStep, false);
    });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
