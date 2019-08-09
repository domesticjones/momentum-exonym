require('jquery-visible');
const $ = jQuery;

export default {
  init() {
  	// Wrap embedded objects and force them into 16:9
  	$('iframe, embed, video').not('.ignore-ratio').wrap('<div class="video-container" />');

  	// HEADER: Responsive Nav Toggle
  	$('#header-nav-toggle').click(e => {
  		const $this = $(e.currentTarget);
  		$this.toggleClass('is-active');
  		$('#nav-responsive').toggleClass('is-active');
  	});
  },
  finalize() {
  	// MODULES: Parallax
  	$(window).on('load resize scroll', () => {
  		const d_scroll = $(window).scrollTop();
  		const w_height = $(window).height();
  		$('.animate-parallax').each((i, e) => {
  			const $this = $(e);
  			const $thisBg = $this.find('.module-bg');
  			const p_position = $this.offset().top;
  			const e_height = $this.outerHeight();
  			const ebg_height = $this.find('.module-bg').outerHeight();
  			const bg_diff = ebg_height - e_height;
  			const dhit_in = p_position - w_height;
  			const dhit_out = p_position + e_height;
  			const dhit_read = p_position - w_height - d_scroll;
  			// Boolean hit Check
  			if (dhit_read <= 0 && d_scroll <= dhit_out) {
  				const per_scrolled = (d_scroll - dhit_in) / (dhit_out - dhit_in);
  				const offset = (bg_diff * per_scrolled);
  				$thisBg.css('transform', `translateY(-${offset}px)`);
  			}
  		});
  	});

  	// MODULES: Animate onScreen
  	$(window).on('load resize scroll', () => {
  		$('.animate-on-enter').each((i, el) => {
  			const $this = $(el);
  			if ($this.visible(true)) {
  				$this.addClass('is-visible');
  			}
  		});
  		$('.animate-on-leave').each((i, el) => {
  			const $this = $(el);
  			if (!$this.visible(true)) {
  				$this.removeClass('is-visible');
  			}
  		});
  	});

    // HEADER: Add Class on Scroll
    $(window).on('load resize scroll', () => {
      if($(window).scrollTop() > 50) {
        $('#header').addClass('is-scrolled');
      } else {
        $('#header').removeClass('is-scrolled');
      }
    });

    // SCHEDULE: Services Select
    let inspections = [];
    $(document).on('click', '#inspection-choose li', (e) => {
      const $this = $(e.currentTarget);
      const val = $this.text();
      const check = inspections.includes(val);
      if(check) {
        const inspectionFound = inspections.indexOf(val);
        if(~inspectionFound) inspections.splice(inspectionFound, 1);
      } else {
        inspections.push(val);
      }
      $this.toggleClass('is-active');
      $('#alg_wc_pif_local_1').val(inspections.join(', '));
    });
  },
};
