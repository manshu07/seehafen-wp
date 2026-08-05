/* global seehafenData */
/**
 * Seehafen theme interactions — 1:1 port of the React SPA behaviors.
 * Menu, dropdowns, scroll reveal, offer carousel, load-more, contact form.
 *
 * @package Seehafen
 */
( function ( $ ) {
	'use strict';

	const revealSelector = [
		'.hero-content',
		'.home-heading',
		'.home-service-card',
		'.split-heading',
		'.section-heading',
		'.process-grid article',
		'.offer-showcase-heading',
		'.offer-showcase-stage',
		'.reference-tile',
		'.page-hero-copy',
		'.page-hero-media',
		'.overview-links-heading',
		'.overview-link-card',
		'.company-about-copy',
		'.company-about-aside',
		'.company-section-heading',
		'.team-grid article',
		'.company-values-column',
		'.primary-service-card',
		'.secondary-service-grid article',
		'.service-detail-header-grid > div',
		'.service-detail-support > img',
		'.service-detail-points li',
		'.references-title h1',
		'.reference-archive-intro',
		'.contact-intro-copy',
		'.contact-direct-panel',
		'.contact-locations',
		'.contact-form',
		'.legal-content',
		'.contact-strip .content > *',
		'.footer-main > *',
		'.footer-bottom',
	].join( ',' );

	const revealMediaSelector = [
		'.page-hero-media',
		'.home-service-card',
		'.offer-showcase-stage',
		'.reference-tile',
		'.overview-link-card',
		'.primary-service-card',
		'.secondary-service-grid article',
		'.service-detail-support > img',
	].join( ',' );

	/**
	 * Scroll reveal — IntersectionObserver with reduced-motion support.
	 *
	 * @return {void}
	 */
	function initScrollReveal() {
		const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		const elements = document.querySelectorAll( revealSelector );

		if ( reducedMotion || ! ( 'IntersectionObserver' in window ) ) {
			elements.forEach( ( element ) => element.classList.add( 'is-revealed' ) );
			return;
		}

		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => {
				if ( ! entry.isIntersecting ) {
					return;
				}

				entry.target.classList.add( 'is-revealed' );
				observer.unobserve( entry.target );
			} );
		}, {
			rootMargin: '0px 0px -8% 0px',
			threshold: 0.12,
		} );

		elements.forEach( ( element ) => {
			element.classList.add( 'scroll-reveal' );

			if ( element.matches( revealMediaSelector ) ) {
				element.classList.add( 'scroll-reveal-media' );
			}

			const siblings = Array.from( element.parentElement ? element.parentElement.children : [] )
				.filter( ( sibling ) => sibling.matches( revealSelector ) );
			const siblingIndex = siblings.indexOf( element );
			element.style.setProperty( '--reveal-delay', `${ Math.min( Math.max( siblingIndex, 0 ), 4 ) * 70 }ms` );

			observer.observe( element );
		} );
	}

	/**
	 * Mobile menu toggle + Escape close.
	 *
	 * @return {void}
	 */
	function initMobileMenu() {
		const nav = document.getElementById( 'main-navigation' );
		const toggle = document.querySelector( '.nav-toggle' );

		if ( ! nav || ! toggle ) {
			return;
		}

		const setOpen = ( open ) => {
			nav.classList.toggle( 'is-open', open );
			document.body.classList.toggle( 'menu-open', open );
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			toggle.setAttribute( 'aria-label', open ? 'Menü schliessen' : 'Menü öffnen' );

			const iconOpen = toggle.querySelector( '[data-nav-icon-open]' );
			const iconClose = toggle.querySelector( '[data-nav-icon-close]' );

			if ( iconOpen ) {
				iconOpen.hidden = open;
			}

			if ( iconClose ) {
				iconClose.hidden = ! open;
			}

			if ( ! open ) {
				closeDropdowns();
			}
		};

		toggle.addEventListener( 'click', () => {
			setOpen( ! nav.classList.contains( 'is-open' ) );
		} );

		document.addEventListener( 'keydown', ( event ) => {
			if ( 'Escape' === event.key ) {
				setOpen( false );
			}
		} );
	}

	/**
	 * Close all dropdown panels.
	 *
	 * @return {void}
	 */
	function closeDropdowns() {
		document.querySelectorAll( '.nav-dropdown' ).forEach( ( dropdown ) => {
			dropdown.classList.remove( 'is-open' );
			const button = dropdown.querySelector( '.nav-dropdown-trigger button' );

			if ( button ) {
				button.setAttribute( 'aria-expanded', 'false' );
				button.setAttribute( 'aria-label', button.getAttribute( 'aria-label' ).replace( ' schliessen', ' öffnen' ) );
			}
		} );
	}

	/**
	 * Dropdown nav toggle.
	 *
	 * @return {void}
	 */
	function initDropdowns() {
		document.querySelectorAll( '.nav-dropdown-trigger button' ).forEach( ( button ) => {
			button.addEventListener( 'click', () => {
				const dropdown = button.closest( '.nav-dropdown' );
				const isOpen = dropdown.classList.contains( 'is-open' );

				closeDropdowns();

				if ( ! isOpen ) {
					dropdown.classList.add( 'is-open' );
					button.setAttribute( 'aria-expanded', 'true' );
					button.setAttribute( 'aria-label', button.getAttribute( 'aria-label' ).replace( ' öffnen', ' schliessen' ) );
				}
			} );
		} );
	}

	/**
	 * Offer showcase carousel — swaps stage from embedded JSON.
	 *
	 * @return {void}
	 */
	function initOfferShowcase() {
		const showcase = document.querySelector( '[data-offer-showcase]' );

		if ( ! showcase ) {
			return;
		}

		const dataEl = showcase.querySelector( '[data-offer-data]' );
		const total = parseInt( showcase.getAttribute( 'data-total' ), 10 ) || 0;

		if ( ! dataEl || 0 === total ) {
			return;
		}

		let items;

		try {
			items = JSON.parse( dataEl.textContent );
		} catch ( error ) {
			return;
		}

		let activeIndex = 0;

		const pad = ( value ) => String( value ).padStart( 2, '0' );

		const render = () => {
			const item = items[ activeIndex ];

			if ( ! item ) {
				return;
			}

			const image = showcase.querySelector( '[data-offer-image]' );
			const counter = showcase.querySelector( '[data-offer-counter]' );
			const label = showcase.querySelector( '[data-offer-label]' );
			const title = showcase.querySelector( '[data-offer-title]' );
			const price = showcase.querySelector( '[data-offer-price]' );
			const location = showcase.querySelector( '[data-offer-location]' );
			const rooms = showcase.querySelector( '[data-offer-rooms]' );
			const area = showcase.querySelector( '[data-offer-area]' );

			if ( image ) {
				image.src = item.image;
				image.alt = item.title;
			}

			if ( counter ) {
				counter.textContent = `${ pad( activeIndex + 1 ) } / ${ pad( total ) }`;
			}

			if ( label ) {
				label.textContent = item.label;
			}

			if ( title ) {
				title.textContent = item.title;
			}

			if ( price ) {
				price.textContent = item.price;
			}

			if ( location ) {
				location.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg> ${ item.location }`;
			}

			if ( rooms ) {
				rooms.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg> ${ item.rooms }`;
			}

			if ( area ) {
				area.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/><path d="m14.5 12.5 2-2"/><path d="m11.5 9.5 2-2"/><path d="m8.5 6.5 2-2"/><path d="m17.5 15.5 2-2"/></svg> ${ item.area }`;
			}
		};

		const showPrevious = () => {
			activeIndex = ( activeIndex - 1 + items.length ) % items.length;
			render();
		};

		const showNext = () => {
			activeIndex = ( activeIndex + 1 ) % items.length;
			render();
		};

		const prev = showcase.querySelector( '[data-offer-prev]' );
		const next = showcase.querySelector( '[data-offer-next]' );

		if ( prev ) {
			prev.addEventListener( 'click', showPrevious );
		}

		if ( next ) {
			next.addEventListener( 'click', showNext );
		}

		items.forEach( ( item ) => {
			const preload = new Image();
			preload.src = item.image;
		} );
	}

	/**
	 * Reference load-more.
	 *
	 * @return {void}
	 */
	function initReferenceLoadMore() {
		const grid = document.querySelector( '[data-reference-grid]' );
		const button = document.querySelector( '[data-reference-more]' );

		if ( ! grid || ! button ) {
			return;
		}

		const total = parseInt( grid.getAttribute( 'data-total' ), 10 ) || 0;
		let offset = grid.querySelectorAll( '.reference-tile' ).length;

		button.addEventListener( 'click', () => {
			const formData = new FormData();
			formData.append( 'action', 'seehafen_load_more' );
			formData.append( 'nonce', seehafenData.contactNonce );
			formData.append( 'offset', String( offset ) );
			formData.append( 'total', String( total ) );

			button.disabled = true;

			fetch( seehafenData.ajaxUrl, {
				method: 'POST',
				body: formData,
			} )
				.then( ( response ) => response.json() )
				.then( ( result ) => {
					if ( ! result.success || ! result.data.html ) {
						button.hidden = true;
						return;
					}

					grid.insertAdjacentHTML( 'beforeend', result.data.html );
					offset = result.data.next;
					button.setAttribute( 'aria-expanded', 'true' );

					if ( ! result.data.has_more ) {
						button.hidden = true;
					}
				} )
				.catch( () => {
					button.hidden = true;
				} )
				.finally( () => {
					button.disabled = false;
				} );
		} );
	}

	/**
	 * Scroll to anchor on initial load with hash.
	 *
	 * @return {void}
	 */
	function initHashScroll() {
		const sectionId = window.location.hash.slice( 1 );

		if ( sectionId ) {
			window.requestAnimationFrame( () => {
				const target = document.getElementById( sectionId );

				if ( target ) {
					target.scrollIntoView();
				}
			} );
		} else {
			window.scrollTo( 0, 0 );
		}
	}

	document.addEventListener( 'DOMContentLoaded', () => {
		initMobileMenu();
		initDropdowns();
		initScrollReveal();
		initOfferShowcase();
		initReferenceLoadMore();
		initHashScroll();
	} );
} )( window.jQuery );
