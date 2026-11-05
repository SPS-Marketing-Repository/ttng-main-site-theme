(function($) {
  $(function() {

    // Passive event listeners for CWV
    $.event.special.touchstart = {
      setup: function(_, ns, handle) {
        this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
      }
    };
    $.event.special.touchmove = {
      setup: function(_, ns, handle) {
        this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
      }
    };
    $.event.special.wheel = {
      setup: function(_, ns, handle) {
        this.addEventListener("wheel", handle, { passive: true });
      }
    };
    $.event.special.mousewheel = {
      setup: function(_, ns, handle) {
        this.addEventListener("mousewheel", handle, { passive: true });
      }
    };

    // YouTube iframe privacy enhancement
    $('iframe[src*="https://www.youtube.com/"]').each(function () {
      this.src = this.src.replace("https://www.youtube.com/", "https://www.youtube-nocookie.com/");
    });

    // Adaptive screen-based content removal
    $(window).on('resize', debounce(function() {
      const windowW = $(window).width();
      if (windowW > 992) {
        $('.show-900').remove();
      } else {
        $('.hide-900').remove();
      }
    }, 150)).trigger('resize');

    // Smooth scrolling for in-page anchor links — offset by the fixed
    // header (top bar + header) so the target isn't hidden underneath it
    $('a[href*="#"]:not([href="#"])').click(function(e) {
      if (location.pathname === this.pathname && location.hostname === this.hostname) {
        const target = $(this.hash);
        if (target.length) {
          e.preventDefault();
          const headerOffset = ($('#site-header-wrap').outerHeight() || 0) + 20;
          $('html, body').animate({ scrollTop: target.offset().top - headerOffset }, 1000);
        }
      }
    });

    // Sticky header: once the top bar and header have scrolled out of view,
    // the header pins to the top as a compact navy bar with a smaller logo
    // (header.css body.header-is-stuck). Measured while unstuck only.
    const $body = $('body');
    function ttng_stickPoint() {
      return ($('.top-bar').outerHeight() || 0) + ($('.site-header').outerHeight() || 0);
    }
    let stickAt = ttng_stickPoint();

    $(window).on('resize.ttng_header', debounce(function() {
      if (!$body.hasClass('header-is-stuck')) {
        stickAt = ttng_stickPoint();
      }
    }, 150));

    $(window).on('scroll.ttng_header', function() {
      $body.toggleClass('header-is-stuck', $(window).scrollTop() > stickAt);
    }).trigger('scroll.ttng_header');

    const reduceMotionPref = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const canObserve = 'IntersectionObserver' in window;

    // Stat numbers count up when they scroll into view (e.g. "1100+").
    // Screen readers get the final value straight away via .sr-only.
    if (canObserve && !reduceMotionPref) {
      const statObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (!entry.isIntersecting) {
            return;
          }
          observer.unobserve(entry.target);
          ttng_countUp(entry.target);
        });
      }, { threshold: 0.5 });

      $('.intro-card__stat').each(function() {
        const raw = $(this).text().trim();
        const match = raw.match(/^([^\d]*)([\d,]+(?:\.\d+)?)(.*)$/);
        if (!match) {
          return;
        }
        $(this)
          .data({ prefix: match[1], target: parseFloat(match[2].replace(/,/g, '')), comma: match[2].indexOf(',') > -1, decimals: (match[2].split('.')[1] || '').length, suffix: match[3], raw: raw })
          .empty()
          .append($('<span aria-hidden="true" class="count-up__value"></span>').text(match[1] + '0' + match[3]))
          .append($('<span class="sr-only"></span>').text(raw));
        statObserver.observe(this);
      });
    }

    function ttng_countUp(el) {
      const $el = $(el);
      const $value = $el.find('.count-up__value');
      const target = $el.data('target');
      const decimals = $el.data('decimals');
      const duration = 1800;
      let start = null;

      function ttng_format(n) {
        const fixed = n.toFixed(decimals);
        return $el.data('comma') ? Number(fixed).toLocaleString('en-GB', { minimumFractionDigits: decimals }) : fixed;
      }

      function ttng_step(now) {
        if (!start) {
          start = now;
        }
        const progress = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
        $value.text($el.data('prefix') + ttng_format(target * eased) + $el.data('suffix'));
        if (progress < 1) {
          window.requestAnimationFrame(ttng_step);
        } else {
          $value.text($el.data('raw'));
        }
      }
      window.requestAnimationFrame(ttng_step);
    }

    // Content animates in once as it scrolls into view. It stays visible without
    // JS or for reduced-motion users, because the classes are only added here.
    // Variants are in additions.css; page headers animate on load in page_header.css.
    if (canObserve && !reduceMotionPref) {
      const revealObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

      const ttng_reveal = function(selector, variant, stagger) {
        $(selector)
          // The hero animates itself, and the split text column moves as one piece.
          .not('.module--home-banner *, .module--page-header *, .split-content__text *')
          .each(function() {
            const box = this.getBoundingClientRect();
            const track = this.closest('.timeline--story, .news-carousel__track, .people-slider--slider .people-slider__track');
            // Already on screen: leave it visible, so it can't flash or delay the first paint.
            // Mostly off-screen in a carousel: leave it visible, or sliding to it shows a blank card.
            if (box.top < window.innerHeight || (track && box.left + box.width / 2 >= track.getBoundingClientRect().right)) {
              return;
            }
            // Fade only in a carousel: vertical movement in a scrolling track can flash a scrollbar.
            const itemVariant = track ? 'fade' : variant;
            if (stagger) {
              // Position within its row: the item's column among its siblings
              const $col = $(this).parent().is('[class*="col"], li') ? $(this).parent() : $(this);
              this.style.setProperty('--reveal-delay', ($col.index() % 4) * 0.12 + 's');
            }
            this.classList.add('reveal');
            if (itemVariant) {
              this.classList.add('reveal--' + itemVariant);
            }
            revealObserver.observe(this);
          });
      };

      // Cards and row items: fade up, one after another along the row
      ttng_reveal('.intro-card, .grid-card, .news-card, .icon-item, .icon-card, .brochure-card, .person-card, .faq-item, .logo-strip__item, .feature-box, .boxed-list__column, .contact-card, .testimonials__benefit, .timeline__item', '', true);

      // Headings, text blocks and whole panels: a single fade up
      ttng_reveal('.section-header, .featured-story__card, .tabs__header-link, .icon-cards__header-link, .faq-links__sidebar, .become-member__text .contact-details, .become-member__links, .become-member__form, .logo-strip__title, .section-ctas--below, .fw-block:not(.fw-block--image), .fw-block--image-panel, .full-width-content__inner > .section-label, .full-width-content__inner > .section-ctas, .quote-panel, .testimonial-slider, .testimonials__note, .feature-boxes__footer, .download__intro .section-ctas, .module--cta-band .contact-details', '', false);

      // Split Content: text and media come in from their own side
      ttng_reveal('.split-content--media-right .split-content__text, .split-content--media-left .split-content__media', 'from-left', true);
      ttng_reveal('.split-content--media-right .split-content__media, .split-content--media-left .split-content__text', 'from-right', true);

      // Images: fade in while growing slightly into place
      ttng_reveal('.split-content__image img, .ttng-tabs__media img, .fw-block--image img, .download__media img, .quote-panel__media img', 'scale', false);

      // Full-bleed split image: a simple fade, as it's positioned against the section edge
      ttng_reveal('.split-content__bleed', 'fade', false);
    }

    // FAQ accordion: animates the native <details> height and fade. Without JS,
    // or for reduced-motion users, the browser opens and closes them instantly.
    if (!reduceMotionPref && 'animate' in document.documentElement) {
      const faqToggle = function(item, open) {
        const panel = item.querySelector('.faq-item__panel');
        // Start from where it is now, so a quick second click reverses smoothly
        const startHeight = item.open ? panel.getBoundingClientRect().height : 0;
        const startOpacity = item.open ? window.getComputedStyle(panel).opacity : 0;

        if (item.ttngFaqAnimation) {
          item.ttngFaqAnimation.onfinish = null;
          item.ttngFaqAnimation.cancel();
        }

        item.classList.toggle('is-closing', !open);
        item.open = true; // Must be open to measure the panel and to animate it shut

        const animation = panel.animate(
          [
            { height: startHeight + 'px', opacity: startOpacity },
            { height: (open ? panel.scrollHeight : 0) + 'px', opacity: open ? 1 : 0 }
          ],
          { duration: 450, easing: 'cubic-bezier(.4, 0, .2, 1)' }
        );

        animation.onfinish = function() {
          item.ttngFaqAnimation = null;
          item.classList.remove('is-closing');
          item.open = open;
        };

        item.ttngFaqAnimation = animation;
      };

      // Native exclusive accordions close the others instantly, so take that over
      $('.faq-item[name]').each(function() {
        this.dataset.faqExclusive = 'true';
        this.removeAttribute('name');
      });

      $(document).on('click.ttngFaq', '.faq-item__question', function(event) {
        event.preventDefault();

        const item = this.parentNode;
        const opening = !item.open || item.classList.contains('is-closing');

        if (opening && item.dataset.faqExclusive) {
          $(item).siblings('.faq-item[open]:not(.is-closing)').each(function() {
            faqToggle(this, false);
          });
        }

        faqToggle(item, opening);
      });
    }

    // Fade slider (.ttng-slider) — used by the Home Banner module.
    // Autoplays, pauses on hover/focus and has a pause button (WCAG 2.2.2).
    // Starts paused for users who prefer reduced motion.
    $('.ttng-slider').each(function() {
      const $slider = $(this);
      const $slides = $slider.find('.ttng-slider__slide');
      const $dots = $slider.find('.ttng-slider__dot');
      const $pause = $slider.find('.ttng-slider__pause');
      const interval = parseInt($slider.data('interval'), 10) || 5000;
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      let current = 0;
      let timer = null;
      let userPaused = reduceMotion;

      if ($slides.length < 2) {
        return;
      }

      function ttng_goTo(index) {
        current = (index + $slides.length) % $slides.length;
        $slides.removeClass('is-active').attr('aria-hidden', 'true')
          .eq(current).addClass('is-active').removeAttr('aria-hidden');
        $dots.removeClass('is-active').removeAttr('aria-current')
          .eq(current).addClass('is-active').attr('aria-current', 'true');
        // Slides move horizontally (staging Swiper look); CSS handles the easing
        $slider.find('.ttng-slider__track').css('transform', 'translateX(' + (-100 * current) + '%)');
      }

      function ttng_stop() {
        clearInterval(timer);
        timer = null;
      }

      function ttng_start() {
        ttng_stop();
        if (!userPaused) {
          timer = setInterval(function() { ttng_goTo(current + 1); }, interval);
        }
      }

      function ttng_setPaused(paused) {
        userPaused = paused;
        $slider.toggleClass('is-paused', paused);
        $pause.attr('aria-label', paused ? $pause.data('label-play') : $pause.data('label-pause'));
        if (paused) {
          ttng_stop();
        } else {
          ttng_start();
        }
      }

      $dots.on('click.ttng_slider', function() {
        ttng_goTo(parseInt($(this).data('slide'), 10));
        ttng_start();
      });

      $pause.on('click.ttng_slider', function() {
        ttng_setPaused(!userPaused);
      });

      $slider
        .on('mouseenter.ttng_slider focusin.ttng_slider', ttng_stop)
        .on('mouseleave.ttng_slider focusout.ttng_slider', ttng_start);

      ttng_setPaused(userPaused);
    });

    // Navigation submenus: the toggle buttons added by TTNG_Mega_Menu_Walker.
    // Desktop also opens on hover (header.css); only one stays open per menu.
    function ttng_closeSubmenus($scope) {
      $scope.find('.is-open').removeClass('is-open')
        .children('.nav-toggle').attr('aria-expanded', 'false');
    }

    $('.nav-toggle').on('click.ttng_nav', function() {
      const $toggle = $(this);
      const $item = $toggle.parent('li');
      const open = !$item.hasClass('is-open');
      ttng_closeSubmenus($item.parent());
      $item.toggleClass('is-open', open);
      $toggle.attr('aria-expanded', open);
    });

    // Escape closes the open desktop panel and returns focus to its toggle
    $('.site-header__nav').on('keydown.ttng_nav', function(e) {
      const $open = $(this).find('li.is-open').first();
      if (e.key === 'Escape' && $open.length) {
        ttng_closeSubmenus($(this));
        $open.children('.nav-toggle').trigger('focus');
      }
    });

    // Clicking elsewhere or tabbing out of the desktop nav closes it
    $(document).on('click.ttng_nav focusin.ttng_nav', function(e) {
      if (!$(e.target).closest('.site-header__nav').length) {
        ttng_closeSubmenus($('.site-header__nav'));
      }
    });

    // Mega panels (inc/mega-menu.php): hovering or focusing a child link
    // swaps the image/description preview beside it.
    $('.mega-panel').each(function() {
      const $panel = $(this);
      const $tabs = $panel.find('.mega-panel__tab');
      const $previews = $panel.find('.mega-panel__preview');

      $tabs.on('mouseenter.ttng_mega focus.ttng_mega', function() {
        const target = $(this).data('mega-preview');
        $tabs.removeClass('is-active');
        $(this).addClass('is-active');
        $previews.removeClass('is-active').filter('#' + target).addClass('is-active');
      });
    });

    // Testimonials slider: cross-fade, dots, autoplay that stops on
    // hover/focus and for reduced-motion users.
    $('.testimonial-slider').each(function() {
      const $slider = $(this);
      const $cards = $slider.find('.testimonial-card');
      const $dots = $slider.find('.testimonial-slider__dot');
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      let current = 0;
      let timer = null;

      if ($cards.length < 2) {
        return;
      }

      $slider.addClass('is-enhanced');
      $cards.not(':first').attr('aria-hidden', 'true');

      function ttng_showTestimonial(index) {
        current = (index + $cards.length) % $cards.length;
        $cards.removeClass('is-active').attr('aria-hidden', 'true')
          .eq(current).addClass('is-active').removeAttr('aria-hidden');
        $dots.removeClass('is-active').removeAttr('aria-current')
          .eq(current).addClass('is-active').attr('aria-current', 'true');
      }

      function ttng_play() {
        clearInterval(timer);
        if (!reduceMotion) {
          timer = setInterval(function() { ttng_showTestimonial(current + 1); }, 6000);
        }
      }

      $dots.on('click.ttng_testimonials', function() {
        ttng_showTestimonial(parseInt($(this).data('slide'), 10));
        ttng_play();
      });

      $slider
        .on('mouseenter.ttng_testimonials focusin.ttng_testimonials', function() { clearInterval(timer); })
        .on('mouseleave.ttng_testimonials focusout.ttng_testimonials', ttng_play);

      ttng_play();
    });

    // Latest News carousel (staging "What do our Members say?"): native
    // scroll-snap track, arrows scroll one card, dots follow the position.
    $('.news-carousel').each(function() {
      const $carousel = $(this);
      const track = $carousel.find('.news-carousel__track').get(0);
      const $slides = $carousel.find('.news-carousel__slide');
      const $dots = $carousel.find('.news-carousel__dots');
      const $prev = $carousel.find('.news-carousel__arrow--prev');
      const $next = $carousel.find('.news-carousel__arrow--next');

      function ttng_step() {
        return $slides.length > 1 ? $slides.eq(1).position().left - $slides.eq(0).position().left : track.clientWidth;
      }

      function ttng_sync() {
        const step = ttng_step() || 1;
        const perView = Math.max(1, Math.round(track.clientWidth / step));
        const pages = Math.max(1, $slides.length - perView + 1);
        const current = Math.min(pages - 1, Math.round(track.scrollLeft / step));
        if ($dots.children().length !== pages) {
          $dots.empty();
          for (let i = 0; i < pages; i++) {
            $dots.append('<span class="news-carousel__dot"></span>');
          }
        }
        $dots.children().removeClass('is-active').eq(current).addClass('is-active');
        $prev.prop('disabled', track.scrollLeft <= 2);
        $next.prop('disabled', track.scrollLeft + track.clientWidth >= track.scrollWidth - 2);
        $carousel.find('.news-carousel__controls').toggle(pages > 1);
      }

      $prev.on('click.ttng_news', function() { track.scrollBy({ left: -ttng_step(), behavior: 'smooth' }); });
      $next.on('click.ttng_news', function() { track.scrollBy({ left: ttng_step(), behavior: 'smooth' }); });
      $(track).on('scroll.ttng_news', debounce(ttng_sync, 60));
      $(window).on('resize.ttng_news', debounce(ttng_sync, 150));
      ttng_sync();
    });

    // Tabs module: WAI-ARIA tabs pattern with arrow, Home and End keys.
    // Panels stay stacked and visible if this never runs.
    $('.ttng-tabs').each(function() {
      const $wrap = $(this);
      const $list = $wrap.children('.ttng-tabs__list');
      const $tabs = $list.children('.ttng-tabs__tab');
      const $panels = $wrap.children('.ttng-tabs__panel');

      if (!$tabs.length) {
        return;
      }

      function ttng_selectTab(index, focus) {
        $tabs.attr({ 'aria-selected': 'false', tabindex: '-1' });
        $tabs.eq(index).attr('aria-selected', 'true').removeAttr('tabindex');
        $panels.prop('hidden', true).eq(index).prop('hidden', false);
        if (focus) {
          $tabs.eq(index).trigger('focus');
        }
      }

      $panels.attr({ role: 'tabpanel', tabindex: '0' });
      $list.prop('hidden', false);
      $wrap.addClass('is-enhanced');
      ttng_selectTab(0, false);

      $tabs.on('click.ttng_tabs', function() {
        ttng_selectTab($tabs.index(this), false);
      });

      $tabs.on('keydown.ttng_tabs', function(e) {
        const count = $tabs.length;
        const index = $tabs.index(this);
        const targets = { ArrowRight: (index + 1) % count, ArrowLeft: (index - 1 + count) % count, Home: 0, End: count - 1 };
        // Tabs in a column also move with up and down, which otherwise scroll the page
        if ($list.attr('aria-orientation') === 'vertical') {
          targets.ArrowDown = targets.ArrowRight;
          targets.ArrowUp = targets.ArrowLeft;
        }
        if (Object.prototype.hasOwnProperty.call(targets, e.key)) {
          e.preventDefault();
          ttng_selectTab(targets[e.key], true);
        }
      });
    });

    // Story timeline carousel: previous/next scroll the native scroll-snap track
    $('.timeline-carousel').each(function() {
      const $carousel = $(this);
      const track = $carousel.children('.timeline--story').get(0);
      const $prev = $carousel.children('.timeline-carousel__arrow--prev');
      const $next = $carousel.children('.timeline-carousel__arrow--next');

      function ttng_updateStoryArrows() {
        const max = track.scrollWidth - track.clientWidth;
        $prev.add($next).prop('hidden', max <= 1);
        $prev.prop('disabled', track.scrollLeft <= 1);
        $next.prop('disabled', track.scrollLeft >= max - 1);
      }

      function ttng_scrollStory(direction) {
        const smooth = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const step = $(track).children().first().outerWidth(true) || track.clientWidth;
        track.scrollBy({ left: direction * (step + 25), behavior: smooth ? 'smooth' : 'auto' });
      }

      $prev.on('click.ttng_story', function() { ttng_scrollStory(-1); });
      $next.on('click.ttng_story', function() { ttng_scrollStory(1); });
      $(track).on('scroll.ttng_story', debounce(ttng_updateStoryArrows, 60));
      $(window).on('resize.ttng_story', debounce(ttng_updateStoryArrows, 150));
      ttng_updateStoryArrows();
    });

    // People slider: previous/next scroll the native scroll-snap track
    $('.people-slider--slider').each(function() {
      const $slider = $(this);
      const track = $slider.children('.people-slider__track').get(0);
      const $prev = $slider.children('.people-slider__arrow--prev');
      const $next = $slider.children('.people-slider__arrow--next');

      function ttng_updateArrows() {
        const max = track.scrollWidth - track.clientWidth;
        $prev.add($next).prop('hidden', max <= 1);
        $prev.prop('disabled', track.scrollLeft <= 1);
        $next.prop('disabled', track.scrollLeft >= max - 1);
      }

      function ttng_scrollSlider(direction) {
        const smooth = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        track.scrollBy({ left: direction * track.clientWidth * 0.8, behavior: smooth ? 'smooth' : 'auto' });
      }

      $prev.on('click.ttng_people', function() { ttng_scrollSlider(-1); });
      $next.on('click.ttng_people', function() { ttng_scrollSlider(1); });
      $(track).on('scroll.ttng_people', debounce(ttng_updateArrows, 60));
      $(window).on('resize.ttng_people', debounce(ttng_updateArrows, 150));
      ttng_updateArrows();
    });

    // Mobile off-canvas menu toggle — body.active also drives the
    // hamburger→X transform (see header.css). The panel is moved
    // off-screen via CSS (top:-100svh), not display:none, so without
    // aria-hidden/inert its links would stay in the tab order at all
    // times — set both here so screen reader/keyboard users only ever
    // reach it while it's actually open, and inert #main/footer while
    // it's open so Tab can't leave the panel into content behind it.
    function setOffCanvasOpen(open) {
      $('#slide-div, .mobile-overflow, body, .hamburger').toggleClass('active', open);
      $('#trigger').attr('aria-expanded', open);
      $('#slide-div').attr('aria-hidden', !open).prop('inert', !open);
      $('#main, footer').prop('inert', open);
    }

    $('#trigger').on('click', function(e) {
      e.preventDefault();
      setOffCanvasOpen(!$('#trigger').hasClass('active'));
    });

    // Close off-canvas menu on Escape, returning focus to the trigger
    $(document).on('keydown', function(e) {
      if (e.key === 'Escape' && $('#trigger').hasClass('active')) {
        setOffCanvasOpen(false);
        $('#trigger').trigger('focus');
      }
    });

    // Utility: debounce function
    function debounce(func, wait) {
      let timeout;
      return function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, arguments), wait);
      };
    }

    // Simple accordion (FAQ-style modules) — not a Bootstrap component
    $('.accordion-header').click(function () {
      const $header = $(this);
      const $content = $header.next('.accordion-content');
      $('.accordion-content').not($content).slideUp();
      $('.accordion-header').not($header).removeClass('active');
      $content.stop(true, true).slideToggle();
      $header.toggleClass('active');
    });

  });
})(jQuery);

/**
 * GA4 / GTM: push `form_submit` to the dataLayer when a Contact Form 7 form
 * is sent successfully. Form ID, form name and page path only — never field
 * values. In GTM: Custom Event trigger "form_submit" → GA4 event tag.
 * GTM itself is loaded by inc/tracking.php (Site Settings > Tracking).
 */
document.addEventListener('wpcf7mailsent', function (event) {
  var form = event.target;
  window.dataLayer = window.dataLayer || [];
  window.dataLayer.push({
    event: 'form_submit',
    form_id: String(event.detail && event.detail.contactFormId ? event.detail.contactFormId : ''),
    form_name: form && form.getAttribute ? (form.getAttribute('aria-label') || '') : '',
    form_location: window.location.pathname
  });
}, false);
