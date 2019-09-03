require('jquery-visible');
const $ = jQuery;

export default {
  init() {
  	// Wrap embedded objects and force them into 16:9
  	$('iframe, embed, video').not('.ignore-ratio').wrap('<div class="video-container" />');

  	// HEADER: Responsive Nav Toggle
  	$('#header-nav-toggle').click(e => {
      e.preventDefault();
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

    // SCHEDULE: Submit Order Details as Order Notes
    $(document).on('click', '#checkout_form a', () => {
      const sup = $('#details-supervisor-name').val();
      const supTel = $('#details-supervisor-phone').val();
      const sqft = $('#details-sqft').val();
      const address = $('#details-address').val();
      const lot = $('#details-lot').val();
      const subdivision = $('#details-subdivision').val();
      const city = $('#details-city').val();
      const state = $('#details-state').val();
      const zip = $('#details-zip').val();
      const manualj = $('#details-manualj').val();
      const services = $('.alg-pif-dd').text();
      let servicesPrint = '';
      if(services.length > 0) {
        servicesPrint = `[services]${services}[/services]`;
      }
      let areaPrint = '';
      if(lot.length > 0 || subdivision.length > 0) {
        areaPrint = `[area]${lot} - ${subdivision}[/area]`;
      }
      const details = `${servicesPrint}[sup]${sup} (${supTel})[/sup][sqft]${sqft} sqft[/sqft]${areaPrint}[address]${address}[/address][locale]${city}, ${state} ${zip}[/locale][manualj]${manualj}[/manualj]`;
      $('#customer_notes_text').val(details);
      const custNotes = $('#customer_notes_text').val();
      $('#customer_notes').val(custNotes);
      if(sup.length && supTel.length && sqft.length && lot.length && subdivision.length && city.length && state.length && zip.length) {
        $('#checkout_form').submit();
      } else {
        alert('Supervisor name, phone, total square feet, lot, subdivision, and city/state/zip are required.');
      }
    });

    // ADMIN: Date Filter Presets
    $('#admin-account-filters .admin-account-filter').click(e => {
      e.preventDefault();
      const $this = $(e.currentTarget);
      const val = $this.data('date');
      $('#admin-filter-date').val(val);
      $('#admin-account-filters').submit();
    });

    // ADMIN: Show Change Date Button onChange
    $('#admin-filter-date').change(() => {
      $('#account-filters-submit').addClass('is-active');
    });
    $('#admin-filter-search').change(() => {
      $('#account-browse-submit').addClass('is-active');
    });
  },
};
