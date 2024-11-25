function mobileDrop() {
	(function($) {
		$('#mobile-menu').unbind('click').bind('click', function() {
			$('#navigation, #mobile-menu, body').toggleClass('active');
			$('#menu-text').html($('#menu-text').html() == 'Close' ? 'Menu' : 'Close');
		});

		$('nav.mobile_nav ul li.menu-item-has-children').click(function(event){
			event.preventDefault();
			$(this).find('ul').first().slideToggle();

		});
	})(jQuery);
}


function CHD_scripts() {
	(function($) {

		$('.back-to-top').click(function() {
			$("html, body").animate({scrollTop: 0}, 1000);
		})

		if($('.cfsource-writers').length) {
			var creativeBadge = '<div class="creative-badge" aria-label="This is Creative Commonwealth content"></div>';
			if($('.intro-content').length) {
				$('.intro-content h1').prepend(creativeBadge);
			}
			else if($('.main-page-content').length) {
				$('.main-page-content .margins-container .left-column').prepend(creativeBadge);
			}
		}

		$(function() {
			if($('.using-mouse')) {
				$('a[href*="#"]:not([href="#"])').click(function() {
					if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
						var target = $(this.hash);
						var offsetAmount = target.offset().top - 100;
						if($('.single-grants-page-template').length) {
							offsetAmount = offsetAmount - 50;
						}
						target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
						if (target.length) {
							$('html, body').animate({
								scrollTop: (offsetAmount)
							}, 1000);
							return false;
						}
					}
				});
			}
		});

		// Single Programme

		if($('.program-list .view-content')) {
			$('.program-list .view-content').click(function() {
				$(this).siblings('.hide-content').slideToggle();
				var spanText = $(this).children('.more-less');
				if(spanText.text() == 'more') {
					spanText.text('less');
				} else {
					spanText.text('more');
				}
			})
		}

		if($('.program-controls')) {
			$('.program-controls .day').click(function() {
				var day = $(this).data('day');
				var container = $(this).closest('.day-control-container');
				$('.program-controls .day, .day-control-container').removeClass('active');
				container.addClass('active');
				$(this).addClass('active');
				$('.day-container').removeClass('active');
				$('#' + day).addClass('active');
			})
		}

		// Let the document know when the mouse is being used
		document.body.addEventListener('mousedown', function() {
			document.body.classList.add('using-mouse');
		});
		document.body.addEventListener('keydown', function() {
			document.body.classList.remove('using-mouse');
		});

		if($('.download-link').length > -1) {
			var br = $('.download-link').find('br').remove();

		}

		$('.tabs h5').click(function() {
			var time = $(this).data('time');
			$('.tabs h5').removeClass('active');
			$(this).addClass('active');
			$('.events').removeClass('active');
			$('.' + time + '-events').addClass('active');
			$('.' + time + '-events .single-event-block').css('display', 'grid');
		})

		if($(window).width() < 991) {
			$('#search-trigger').appendTo('.mobile-show');
		}


		$('#search-trigger').click(function() {
			$('#search-modal').addClass('active');
			$('.search-wrap input[type="text"]#search-input-box').focus();
			$('#close').click(function() {
				$('#search-modal').removeClass('active');
			})
		})

		if($('#map').length) {

			var defaultCenter = new google.maps.LatLng(11.596100329701923, -34.60290874354731);

			var map = new google.maps.Map(document.getElementById("map"), {
				zoom: 2,
				center: defaultCenter,
				mapTypeId: "terrain",
			});

			// map.addListener("zoom_changed", function() {
			// 	map.addListener("center_changed", function() {
			// 		console.log('lat', map.getCenter().lat());
			// 		console.log('lng', map.getCenter().lng());
			// 	});
			// });

			var regionPos = {
				'Africa' : [2.534634813190683, 21.40854885120856],
				'Americas' : [33.49361307660098, -75.20837749354732],
				'Asia' : [16.569719221758866, 98.19982563145268],
				'Europe' : [47.68597707622838, 10.924435006452681],
				'Pacific' : [-19.477636425363034, 165.83769188692224],
			};

			$('.region .accordion-trigger').click(function() {
				if($(this).hasClass('active')) {
					map.setZoom(2);
					map.panTo(defaultCenter);
				}
				else {
					var regionName = $(this).find('.visible-text').text();
					if(regionName in regionPos) {
						var coords = regionPos[regionName];
						var latLng = new google.maps.LatLng(coords[0], coords[1]);
						map.setZoom(3);
						map.panTo(latLng);
					}
				}
			})




			var markers = jsObj.markers;


			var objInfo = [];

			var mapMarkers = [];
			var lastRegion = null;

			var infowindow = new google.maps.InfoWindow();

			function createMarkers(countryCode) {
				if (markers.hasOwnProperty(countryCode)) {
					var result = countryCodes().filter(obj => {
						return obj.Code === countryCode
					});
					if(result.length) {

						result = result[0];
						var latLng = new google.maps.LatLng(result.Lat, result.Lng);

						var marker = new google.maps.Marker({
							position: latLng,
							map: map,
							animation: google.maps.Animation.DROP,
							title: markers[countryCode].name,
						});


						var contentString =
						'<div class="map-info-content">' +
						'<div class="siteNotice">' +
						"</div>" +
						'<span class="country-name-pin">' + markers[countryCode].name + '</span>' +
						'<div class="bodyContent">';

						if(markers[countryCode].link) {
							contentString += '<a href="' + markers[countryCode].link + '">View projects</a>';
						}


						contentString += "</div>" +
						"</div>";


						google.maps.event.addListener(marker, 'click', function(){
							$('.country .c-name').removeClass('highlight');
							infowindow.close();
							infowindow.setContent(contentString);
							infowindow.open(map, marker);

							var countryName = markers[countryCode].name.toLowerCase().replace(/\s/g, '-');
							var thisCountry = $('.country[data-name=' + countryName +']');

							thisCountry.find('.c-name').addClass('highlight');

							var countryGroup = thisCountry.closest('.country-group');
							var countryGroupBtn = $(countryGroup).prev('button');
							var countryGroupName = countryGroupBtn.find('.visible-text').text();

							if(lastRegion !== countryGroupBtn) {
								if(lastRegion) {
									lastRegion.click();
								}
								countryGroupBtn.click();

								var thisCountryPos = thisCountry.offset();

								setTimeout(function() {
									$('.countries').scrollTop(thisCountryPos.top - 200);
								}, 500);
							}

							lastRegion = countryGroupBtn;

						});



					}
					// else {
					// 	console.log(countryCode);
					// }
				}
			}

			for (var countryCode in markers) {
				createMarkers(countryCode);
			}



			function closeOtherInfo() {
				if (objInfo.length > 0) {
					/* detach the info-window from the marker ... undocumented in the API docs */
					objInfo[0].set("marker", null);
					/* and close it */
					objInfo[0].close();
					/* blank the array */
					objInfo.length = 0;
				}
			}

		}



		function moveGrey() {
			var panelNav = $('.panel-nav');
			var panelLeft = panelNav.offset().left;
			var panelTop = panelNav.offset().top;
			var panelWidth = panelNav.width() + 16;
			var activeItemTop = $('.panel-nav-item.active').offset().top;
			var activeItemHeight = $('.panel-nav-item.active').outerHeight();
			var greyTop = (activeItemTop - panelTop);
			$('.active-sign').width(panelLeft + panelWidth).css({'left': -panelLeft, 'top':greyTop, 'height' : activeItemHeight });
		}


		if($(window).width() > 991) {
			if($('.panel-nav').length > 0){
				moveGrey();
				$('.panel-card').height($('#panelist-0').outerHeight());
			}
		}

		function showActiveCard() {
			var selectCard = $(this).data('card');
			if(!$('#' + selectCard).hasClass('active')) {
				$('.inner-panel').removeClass('active');
				$('#' + selectCard).addClass('active');
				$('.panel-card').height($('#' + selectCard + '.inner-panel').outerHeight());
			}
			$('.panel-nav-item').removeClass('active no-border-bottom');
			$(this).addClass('active');
			$(this).prev().addClass('no-border-bottom');
			if($(window).width() > 991) {
				moveGrey();
			}
		}

		$('.panel-nav-item').click(showActiveCard);

		$('.quick-jump .panelist').click(function() {
			var navItem = $(this).data('card');
			$('.panel-nav-item').removeClass('active');
			$('.panel-nav-item[data-card="' + navItem + '"]').addClass('active');
			$('html, body').animate({
				scrollTop: ($('.panel-nav').offset().top) - 120
			}, 1000);
			if(!$('#' + navItem).hasClass('active')) {
				$('.inner-panel').removeClass('active');
				$('#' + navItem).addClass('active');
			}
			moveGrey();
		})

		$('.button.dropdown-click').click(function() {
			var target = $(this).data('target');
			$('.' + target + '-dropdown').slideToggle();
		})

		$('.timeline-content').height($('.year-group.active').height() + $('.timeline-slider').height());

		$('.timeline-date').click(function() {
			var date = $(this).data('year');
			var prevPos = $('.timeline-date.active').position().left;
			var thisPos = $(this).position().left;
			$('.timeline-bar').width(thisPos + 20);
			if (prevPos > thisPos) {
				var slideDir = 'slide-right';
			} else {
				var slideDir = 'slide-left';
			}
			$('.year-group, .timeline-date').removeClass('active slide-left slide-right').addClass(slideDir);
			setTimeout(function() {
				//$('.year-group').hide();
				$('.timeline-content').height($('#year-' + date).height());
				$('#year-' + date).addClass('active');
			}, 400);
			$(this).addClass('active');
		})

		// Fade In
		$('.lazy').lazyload({
			effect : "fadeIn"
		});

		if($('.prev-cpf').length) {
			$('.prev-cpf').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: true,
				fade: true,
			});
		}

		$('.partner-slider').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: true,
			infinite: false,
			fade: true,
			asNavFor: '.partner-nav-slider'
		});
		$('.partner-nav-slider').slick({
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.partner-slider',
			dots: true,
			centerMode: true,
			focusOnSelect: true
		});

		$('select').SumoSelect();

		$(document).on('click', '.filter-section', function() {
			$(this).toggleClass('active');
			$(this).children('.filter-content').slideToggle();
		})

		if ($('.download-clone')) {
			if($('.file-button').length < 1) {
				var download = $('.download-link');
				download.clone().appendTo( ".download-clone" );
			}
		}

		if($('.read-more').length > 0) {
			$('.read-more').click(function() {
				if($(this).text() == 'Read more') {
					$(this).siblings('.show-expand').slideDown();
					$(this).addClass('active');
					$(this).text('Hide');
				} else {
					$(this).siblings('.show-expand').slideUp();
					$(this).removeClass('active').text('Read more');
				}
			})
		}
		$(document).on('click', '#load-more', function() {
			$(this).children('.button-text').html('<span>Loading <i style="color: white; margin-left: 8px" class="fa fa-spinner fa-pulse"></i></span>');
			var location = window.location;
			var urlPathName = location.pathname;
			if (urlPathName.indexOf('page/') > -1) {
				var pageNumURL = urlPathName.split('page/')[1].match(/\d+/g).map(Number)[0];
				var baseURL = urlPathName.split('page/')[0];
			} else {
				pageNumURL = 1;
				baseURL = urlPathName;
			}
			var urlToGet = location.protocol + '//' + location.host + baseURL + 'page/' + (pageNumURL + 1) + location.search;
			$.get(urlToGet, function(res) {
				var response = $(res);
				window.history.pushState(null, null, urlToGet);
				var content = response.find('.project');

				$('.projects-archive-grid').append(content);
				$('#load-more').children('.button-text').html('Load More');
				if(content.length < 12) {
					$('#load-more').fadeOut('slow');
				}

			})
		})

		$('a').each(function() {
			var a = new RegExp('/' + window.location.host + '/');
			if(!a.test(this.href)) {
				$(this).click(function(event) {
					event.preventDefault();
					event.stopPropagation();
					window.open(this.href, '_blank');
				});
			}
		});

		$('.accordion-trigger').click(function() {
			var labels = $(this).closest('.accordion-block-container').data('labels');
			if($(this).hasClass('active')) {
				if(labels) {
					$(this).find('.fake-button .text').text(labels['closed_label']);
				}
				$(this).removeClass('active').attr('aria-expanded', false);
				$(this).siblings('.accordion-panel').slideUp();
			} else {
				if(labels) {
					$(this).find('.fake-button .text').text(labels['open_label']);
				}
				$(this).addClass('active').attr('aria-expanded', true);
				$(this).siblings('.accordion-panel').slideDown();
			}
		});

		if ($('.eligibility-quiz-container').length) {
			var questions = $('.question-group');
			var total = questions.length;
	 		$('.next-q-btn').click(function() {
				var hit = $(this).data('hit');
				var currentQuestion = $(this).data('index');
				if(hit) {
					updateProgressBar(currentQuestion, questions);
					var nextQ = currentQuestion + 1;
					$('#question-' + currentQuestion).hide();
					if($('#question-' + nextQ).length) {
						$('#question-' + nextQ).show();
					}
					else {
						$('.success-container').show().attr('aria-hidden', false);
						$('.question-container, .progress-container').hide().attr('aria-hidden', true);
					}
				}
				else {
					$('.failure-container').show().attr('aria-hidden', false);
					$('.question-container, .progress-container').hide().attr('aria-hidden', true);
				}

			});
		}

		function updateProgressBar(currentQuestion, questions) {
			var progressBar = $('#progress-bar');
		  var totalQuestions = questions.length;
		  var percentage = (currentQuestion / (totalQuestions)) * 100;
		  progressBar.width(percentage + '%');
		  progressBar.text('Question ' + currentQuestion + 1 + 'out of ' + totalQuestions);
		  progressBar.attr('aria-valuenow', currentQuestion + 1);
		}





		//SITE-WIDE NOTIFICATION

		if (localStorage.getItem('notification') && $('.notification').length > -1){
			var notifyMessage = JSON.parse(localStorage.getItem('notification'));
			if (notifyMessage[0] == 'seen' && notifyMessage[1] == $('.notification .notify-title').text()) {
				$('.notification').remove();
			}
		} else {
			var notifyHeight = $('.notification').outerHeight();
			$('.notification').addClass('active');
			$('.site-header').addClass('notification-showing').css('top', notifyHeight);
			//$('#mobile-menu').css('top', notifyHeight + 24);
		}

		$('.notification .close, .notification .button').click(function(){
			$('.site-header').css('top', 0).removeClass('notification-showing');
			//$('#mobile-menu').css('top', 27.5);
			$('.notification').removeClass('active');
			setTimeout(function() {
				$('.notification').remove();
			}, 2000);
			var notifyObj = ['seen', $('.notification .notify-title').text()];
			localStorage.setItem('notification', JSON.stringify(notifyObj));
		})

		$('#filter select').change(function(){
			var filter = $('#filter');
			var filterInfo = filter.serialize();
			$.ajax({
				url:filter.attr('action'),
				data:filterInfo, // form data
				type:filter.attr('method'), // POST
				beforeSend:function(xhr){
					$('.results-container').addClass('active');
				},
				success:function(data){
					filterInfo = filterInfo.replace('&action=filterAjax', '');
					current = window.location.href.split('?')[0];
					window.history.pushState(null, null, current + '?' + filterInfo);
					// filter.find('button').text('Apply filter'); // changing the button label back
					$('.results-container').html(data); // insert data
					$('.results-container').removeClass('active');
					show_filter_info(filter.attr('action'), filter.serialize());
				}
			});
			return false;
		});

		function show_filter_info(url, data) {
			$.ajax({
				url: url,
				data: ({
					action: 'ajax_filter_func',
					get: data
				}),
				type: 'GET',
				beforeSend:function(xhr){
					$('.filter-info').addClass('waiting');
				},
				success:function(data){
					$('.filter-info').html(data); // insert data
					$('.filter-info').removeClass('waiting');
				}
			});
			return false;
		}

		if($('.sidebar-menu').length > 0) {
			if ($(window).width() < 768) {
				$('.sidebar-menu').detach().appendTo('.menu-holder');
			} else {
				// var sideWidth = $('.right-column').width();
				// var sideTop = $('.right-column').offset().top;
				// var sideLeft = $('.right-column').position().left;
				// $('.sidebar-menu').addClass('fixed').css({'left': sideLeft, 'top' : sideTop, 'width' : sideWidth});
			}
		}

		if($(window).width() < 480) {

			// Writers Switcharoo
			if ($('.cwwriters-banner .button').length > 0) {
				$('.cwwriters-banner .button').detach().appendTo($('.cwwriters-banner .right-group'));
			}

			// Grants Deadline Switcharoo
			if ($('.sidebar .deadline-group').length > 0) {
				$('.sidebar .deadline-group').addClass('card').detach().appendTo($('.deadline-holder'));
			}
		}


		//Move right column for Digital Events
		if($('.dig-right').length > 0) {
			$('.dig-right').appendTo('.first-main-container');
		}

		//Copy link
		$('.zoom-copy-container span').click(function(){
			var copyLink = $('.zoom-copy-container span').data('zoom');
			$(this).text('Copied!');
			$('.zoom-copy-container').addClass('copied');
			document.addEventListener('copy', function(e) {
				e.clipboardData.setData('text/plain', copyLink);
				e.preventDefault();
			}, true);
			document.execCommand('copy');
			setTimeout(function() {
				$('.zoom-copy-container span').text('Copy link').removeClass('copied');
				$('.zoom-copy-container').removeClass('copied');
			}, 2000);
		})

		//Load more events
		function loadEvents(x=5, events) {
			$('.events .single-event-block:lt('+x+')').css('display', 'grid');
			// y=x-1;
			// last = $('.events .single-event-block:nth-child('+y+')');
			if (x !== events) {
				$(last).after('<button class="button event-load">Load More</button>');
			}
		}

		if($('.past-events .single-event-block').length > 4) {
			events = $('.past-events .single-event-block').size(); x=5;
			$('.past-events .single-event-block:lt('+x+')').css('display', 'grid');
			$('.past-events').append('<div class="center event-load-container"><button class="button event-load">Load more events</button></div>');
			//loadEvents(5, events);
			$('.event-load').click(function () {
				x = (x+5 <= events) ? x+5 : events;
				$('.past-events .single-event-block:lt('+x+')').css('display', 'grid');
				if (x == events) { $('.event-load-container').remove() }
				// loadEvents(x, events);
			});
		}

	})(jQuery);
}

function on_scroll() {
	(function($) {

		windowHeight = $(window).height();
		scrollTop = $(window).scrollTop();

		//if ($(window).width() > 991) {
		if (scrollTop > 130) {
			$('header, body').addClass('fixed');
		}
		if (scrollTop < 1) {
			$('header, body').removeClass('fixed');
		}
		//}

		if ($('#footer').length > 0) {
			var footerPos = $('#footer').offset().top;
			var footerHeight = $('#footer').outerHeight();
			var columnHeight = $('.column-left').outerHeight();
			// if(footerPos - (scrollTop + columnHeight) < columnHeight) {
			// 	$('.column-left').addClass('stop');
			// } else {
			// 	$('.column-left').removeClass('stop');
			// };
		}

	})(jQuery);
}

jQuery(document).ready(function() {
	CHD_scripts();
	mobileDrop();
});

jQuery(window).resize(function() {
	mobileDrop();
});

jQuery(window).scroll(function() {
	on_scroll();
});

function filterFunction(url, which, value) {
	window.location = url + '&' + which + '=' + value;
}

function countryCodes() {
	return [
		{
			"Code": "AD",
			"Lat": 42.546245,
			"Lng": 1.601554,
			"Country": "Andorra"
		},
		{
			"Code": "AE",
			"Lat": 23.424076,
			"Lng": 53.847818,
			"Country": "United Arab Emirates"
		},
		{
			"Code": "AF",
			"Lat": 33.93911,
			"Lng": 67.709953,
			"Country": "Afghanistan"
		},
		{
			"Code": "AG",
			"Lat": 17.060816,
			"Lng": -61.796428,
			"Country": "Antigua and Barbuda"
		},
		{
			"Code": "AI",
			"Lat": 18.220554,
			"Lng": -63.068615,
			"Country": "Anguilla"
		},
		{
			"Code": "AL",
			"Lat": 41.153332,
			"Lng": 20.168331,
			"Country": "Albania"
		},
		{
			"Code": "AM",
			"Lat": 40.069099,
			"Lng": 45.038189,
			"Country": "Armenia"
		},
		{
			"Code": "AN",
			"Lat": 12.226079,
			"Lng": -69.060087,
			"Country": "Netherlands Antilles"
		},
		{
			"Code": "AO",
			"Lat": -11.202692,
			"Lng": 17.873887,
			"Country": "Angola"
		},
		{
			"Code": "AQ",
			"Lat": -75.250973,
			"Lng": -0.071389,
			"Country": "Antarctica"
		},
		{
			"Code": "AR",
			"Lat": -38.416097,
			"Lng": -63.616672,
			"Country": "Argentina"
		},
		{
			"Code": "AS",
			"Lat": -14.270972,
			"Lng": -170.132217,
			"Country": "American Samoa"
		},
		{
			"Code": "AT",
			"Lat": 47.516231,
			"Lng": 14.550072,
			"Country": "Austria"
		},
		{
			"Code": "AU",
			"Lat": -25.274398,
			"Lng": 133.775136,
			"Country": "Australia"
		},
		{
			"Code": "AW",
			"Lat": 12.52111,
			"Lng": -69.968338,
			"Country": "Aruba"
		},
		{
			"Code": "AZ",
			"Lat": 40.143105,
			"Lng": 47.576927,
			"Country": "Azerbaijan"
		},
		{
			"Code": "BA",
			"Lat": 43.915886,
			"Lng": 17.679076,
			"Country": "Bosnia and Herzegovina"
		},
		{
			"Code": "BB",
			"Lat": 13.193887,
			"Lng": -59.543198,
			"Country": "Barbados"
		},
		{
			"Code": "BD",
			"Lat": 23.684994,
			"Lng": 90.356331,
			"Country": "Bangladesh"
		},
		{
			"Code": "BE",
			"Lat": 50.503887,
			"Lng": 4.469936,
			"Country": "Belgium"
		},
		{
			"Code": "BF",
			"Lat": 12.238333,
			"Lng": -1.561593,
			"Country": "Burkina Faso"
		},
		{
			"Code": "BG",
			"Lat": 42.733883,
			"Lng": 25.48583,
			"Country": "Bulgaria"
		},
		{
			"Code": "BH",
			"Lat": 25.930414,
			"Lng": 50.637772,
			"Country": "Bahrain"
		},
		{
			"Code": "BI",
			"Lat": -3.373056,
			"Lng": 29.918886,
			"Country": "Burundi"
		},
		{
			"Code": "BJ",
			"Lat": 9.30769,
			"Lng": 2.315834,
			"Country": "Benin"
		},
		{
			"Code": "BM",
			"Lat": 32.321384,
			"Lng": -64.75737,
			"Country": "Bermuda"
		},
		{
			"Code": "BN",
			"Lat": 4.535277,
			"Lng": 114.727669,
			"Country": "Brunei"
		},
		{
			"Code": "BO",
			"Lat": -16.290154,
			"Lng": -63.588653,
			"Country": "Bolivia"
		},
		{
			"Code": "BR",
			"Lat": -14.235004,
			"Lng": -51.92528,
			"Country": "Brazil"
		},
		{
			"Code": "BS",
			"Lat": 25.03428,
			"Lng": -77.39628,
			"Country": "Bahamas"
		},
		{
			"Code": "BT",
			"Lat": 27.514162,
			"Lng": 90.433601,
			"Country": "Bhutan"
		},
		{
			"Code": "BV",
			"Lat": -54.423199,
			"Lng": 3.413194,
			"Country": "Bouvet Island"
		},
		{
			"Code": "BW",
			"Lat": -22.328474,
			"Lng": 24.684866,
			"Country": "Botswana"
		},
		{
			"Code": "BY",
			"Lat": 53.709807,
			"Lng": 27.953389,
			"Country": "Belarus"
		},
		{
			"Code": "BZ",
			"Lat": 17.189877,
			"Lng": -88.49765,
			"Country": "Belize"
		},
		{
			"Code": "CA",
			"Lat": 56.130366,
			"Lng": -106.346771,
			"Country": "Canada"
		},
		{
			"Code": "CC",
			"Lat": -12.164165,
			"Lng": 96.870956,
			"Country": "Cocos [Keeling] Islands"
		},
		{
			"Code": "CD",
			"Lat": -4.038333,
			"Lng": 21.758664,
			"Country": "Congo [DRC]"
		},
		{
			"Code": "CF",
			"Lat": 6.611111,
			"Lng": 20.939444,
			"Country": "Central African Republic"
		},
		{
			"Code": "CG",
			"Lat": -0.228021,
			"Lng": 15.827659,
			"Country": "Congo [Republic]"
		},
		{
			"Code": "CH",
			"Lat": 46.818188,
			"Lng": 8.227512,
			"Country": "Switzerland"
		},
		{
			"Code": "CI",
			"Lat": 7.539989,
			"Lng": -5.54708,
			"Country": "Côte d'Ivoire"
		},
		{
			"Code": "CK",
			"Lat": -21.236736,
			"Lng": -159.777671,
			"Country": "Cook Islands"
		},
		{
			"Code": "CL",
			"Lat": -35.675147,
			"Lng": -71.542969,
			"Country": "Chile"
		},
		{
			"Code": "CM",
			"Lat": 7.369722,
			"Lng": 12.354722,
			"Country": "Cameroon"
		},
		{
			"Code": "CN",
			"Lat": 35.86166,
			"Lng": 104.195397,
			"Country": "China"
		},
		{
			"Code": "CO",
			"Lat": 4.570868,
			"Lng": -74.297333,
			"Country": "Colombia"
		},
		{
			"Code": "CR",
			"Lat": 9.748917,
			"Lng": -83.753428,
			"Country": "Costa Rica"
		},
		{
			"Code": "CU",
			"Lat": 21.521757,
			"Lng": -77.781167,
			"Country": "Cuba"
		},
		{
			"Code": "CV",
			"Lat": 16.002082,
			"Lng": -24.013197,
			"Country": "Cape Verde"
		},
		{
			"Code": "CX",
			"Lat": -10.447525,
			"Lng": 105.690449,
			"Country": "Christmas Island"
		},
		{
			"Code": "CY",
			"Lat": 35.126413,
			"Lng": 33.429859,
			"Country": "Cyprus"
		},
		{
			"Code": "CZ",
			"Lat": 49.817492,
			"Lng": 15.472962,
			"Country": "Czech Republic"
		},
		{
			"Code": "DE",
			"Lat": 51.165691,
			"Lng": 10.451526,
			"Country": "Germany"
		},
		{
			"Code": "DJ",
			"Lat": 11.825138,
			"Lng": 42.590275,
			"Country": "Djibouti"
		},
		{
			"Code": "DK",
			"Lat": 56.26392,
			"Lng": 9.501785,
			"Country": "Denmark"
		},
		{
			"Code": "DM",
			"Lat": 15.414999,
			"Lng": -61.370976,
			"Country": "Dominica"
		},
		{
			"Code": "DO",
			"Lat": 18.735693,
			"Lng": -70.162651,
			"Country": "Dominican Republic"
		},
		{
			"Code": "DZ",
			"Lat": 28.033886,
			"Lng": 1.659626,
			"Country": "Algeria"
		},
		{
			"Code": "EC",
			"Lat": -1.831239,
			"Lng": -78.183406,
			"Country": "Ecuador"
		},
		{
			"Code": "EE",
			"Lat": 58.595272,
			"Lng": 25.013607,
			"Country": "Estonia"
		},
		{
			"Code": "EG",
			"Lat": 26.820553,
			"Lng": 30.802498,
			"Country": "Egypt"
		},
		{
			"Code": "EH",
			"Lat": 24.215527,
			"Lng": -12.885834,
			"Country": "Western Sahara"
		},
		{
			"Code": "ER",
			"Lat": 15.179384,
			"Lng": 39.782334,
			"Country": "Eritrea"
		},
		{
			"Code": "ES",
			"Lat": 40.463667,
			"Lng": -3.74922,
			"Country": "Spain"
		},
		{
			"Code": "ET",
			"Lat": 9.145,
			"Lng": 40.489673,
			"Country": "Ethiopia"
		},
		{
			"Code": "FI",
			"Lat": 61.92411,
			"Lng": 25.748151,
			"Country": "Finland"
		},
		{
			"Code": "FJ",
			"Lat": -16.578193,
			"Lng": 179.414413,
			"Country": "Fiji"
		},
		{
			"Code": "FK",
			"Lat": -51.796253,
			"Lng": -59.523613,
			"Country": "Falkland Islands [Islas Malvinas]"
		},
		{
			"Code": "FM",
			"Lat": 7.425554,
			"Lng": 150.550812,
			"Country": "Micronesia"
		},
		{
			"Code": "FO",
			"Lat": 61.892635,
			"Lng": -6.911806,
			"Country": "Faroe Islands"
		},
		{
			"Code": "FR",
			"Lat": 46.227638,
			"Lng": 2.213749,
			"Country": "France"
		},
		{
			"Code": "GA",
			"Lat": -0.803689,
			"Lng": 11.609444,
			"Country": "Gabon"
		},
		{
			"Code": "GB",
			"Lat": 55.378051,
			"Lng": -3.435973,
			"Country": "United Kingdom"
		},
		{
			"Code": "GD",
			"Lat": 12.262776,
			"Lng": -61.604171,
			"Country": "Grenada"
		},
		{
			"Code": "GE",
			"Lat": 42.315407,
			"Lng": 43.356892,
			"Country": "Georgia"
		},
		{
			"Code": "GF",
			"Lat": 3.933889,
			"Lng": -53.125782,
			"Country": "French Guiana"
		},
		{
			"Code": "GG",
			"Lat": 49.465691,
			"Lng": -2.585278,
			"Country": "Guernsey"
		},
		{
			"Code": "GH",
			"Lat": 7.946527,
			"Lng": -1.023194,
			"Country": "Ghana"
		},
		{
			"Code": "GI",
			"Lat": 36.137741,
			"Lng": -5.345374,
			"Country": "Gibraltar"
		},
		{
			"Code": "GL",
			"Lat": 71.706936,
			"Lng": -42.604303,
			"Country": "Greenland"
		},
		{
			"Code": "GM",
			"Lat": 13.443182,
			"Lng": -15.310139,
			"Country": "Gambia"
		},
		{
			"Code": "GN",
			"Lat": 9.945587,
			"Lng": -9.696645,
			"Country": "Guinea"
		},
		{
			"Code": "GP",
			"Lat": 16.995971,
			"Lng": -62.067641,
			"Country": "Guadeloupe"
		},
		{
			"Code": "GQ",
			"Lat": 1.650801,
			"Lng": 10.267895,
			"Country": "Equatorial Guinea"
		},
		{
			"Code": "GR",
			"Lat": 39.074208,
			"Lng": 21.824312,
			"Country": "Greece"
		},
		{
			"Code": "GS",
			"Lat": -54.429579,
			"Lng": -36.587909,
			"Country": "South Georgia and the South Sandwich Islands"
		},
		{
			"Code": "GT",
			"Lat": 15.783471,
			"Lng": -90.230759,
			"Country": "Guatemala"
		},
		{
			"Code": "GU",
			"Lat": 13.444304,
			"Lng": 144.793731,
			"Country": "Guam"
		},
		{
			"Code": "GW",
			"Lat": 11.803749,
			"Lng": -15.180413,
			"Country": "Guinea-Bissau"
		},
		{
			"Code": "GY",
			"Lat": 4.860416,
			"Lng": -58.93018,
			"Country": "Guyana"
		},
		{
			"Code": "GZ",
			"Lat": 31.354676,
			"Lng": 34.308825,
			"Country": "Gaza Strip"
		},
		{
			"Code": "HK",
			"Lat": 22.396428,
			"Lng": 114.109497,
			"Country": "Hong Kong"
		},
		{
			"Code": "HM",
			"Lat": -53.08181,
			"Lng": 73.504158,
			"Country": "Heard Island and McDonald Islands"
		},
		{
			"Code": "HN",
			"Lat": 15.199999,
			"Lng": -86.241905,
			"Country": "Honduras"
		},
		{
			"Code": "HR",
			"Lat": 45.1,
			"Lng": 15.2,
			"Country": "Croatia"
		},
		{
			"Code": "HT",
			"Lat": 18.971187,
			"Lng": -72.285215,
			"Country": "Haiti"
		},
		{
			"Code": "HU",
			"Lat": 47.162494,
			"Lng": 19.503304,
			"Country": "Hungary"
		},
		{
			"Code": "ID",
			"Lat": -0.789275,
			"Lng": 113.921327,
			"Country": "Indonesia"
		},
		{
			"Code": "IE",
			"Lat": 53.41291,
			"Lng": -8.24389,
			"Country": "Ireland"
		},
		{
			"Code": "IL",
			"Lat": 31.046051,
			"Lng": 34.851612,
			"Country": "Israel"
		},
		{
			"Code": "IM",
			"Lat": 54.236107,
			"Lng": -4.548056,
			"Country": "Isle of Man"
		},
		{
			"Code": "IN",
			"Lat": 20.593684,
			"Lng": 78.96288,
			"Country": "India"
		},
		{
			"Code": "IO",
			"Lat": -6.343194,
			"Lng": 71.876519,
			"Country": "British Indian Ocean Territory"
		},
		{
			"Code": "IQ",
			"Lat": 33.223191,
			"Lng": 43.679291,
			"Country": "Iraq"
		},
		{
			"Code": "IR",
			"Lat": 32.427908,
			"Lng": 53.688046,
			"Country": "Iran"
		},
		{
			"Code": "IS",
			"Lat": 64.963051,
			"Lng": -19.020835,
			"Country": "Iceland"
		},
		{
			"Code": "IT",
			"Lat": 41.87194,
			"Lng": 12.56738,
			"Country": "Italy"
		},
		{
			"Code": "JE",
			"Lat": 49.214439,
			"Lng": -2.13125,
			"Country": "Jersey"
		},
		{
			"Code": "JM",
			"Lat": 18.109581,
			"Lng": -77.297508,
			"Country": "Jamaica"
		},
		{
			"Code": "JO",
			"Lat": 30.585164,
			"Lng": 36.238414,
			"Country": "Jordan"
		},
		{
			"Code": "JP",
			"Lat": 36.204824,
			"Lng": 138.252924,
			"Country": "Japan"
		},
		{
			"Code": "KE",
			"Lat": -0.023559,
			"Lng": 37.906193,
			"Country": "Kenya"
		},
		{
			"Code": "KG",
			"Lat": 41.20438,
			"Lng": 74.766098,
			"Country": "Kyrgyzstan"
		},
		{
			"Code": "KH",
			"Lat": 12.565679,
			"Lng": 104.990963,
			"Country": "Cambodia"
		},
		{
			"Code": "KI",
			"Lat": -3.370417,
			"Lng": -168.734039,
			"Country": "Kiribati"
		},
		{
			"Code": "KM",
			"Lat": -11.875001,
			"Lng": 43.872219,
			"Country": "Comoros"
		},
		{
			"Code": "KN",
			"Lat": 17.357822,
			"Lng": -62.782998,
			"Country": "Saint Kitts and Nevis"
		},
		{
			"Code": "KP",
			"Lat": 40.339852,
			"Lng": 127.510093,
			"Country": "North Korea"
		},
		{
			"Code": "KR",
			"Lat": 35.907757,
			"Lng": 127.766922,
			"Country": "South Korea"
		},
		{
			"Code": "KW",
			"Lat": 29.31166,
			"Lng": 47.481766,
			"Country": "Kuwait"
		},
		{
			"Code": "KY",
			"Lat": 19.513469,
			"Lng": -80.566956,
			"Country": "Cayman Islands"
		},
		{
			"Code": "KZ",
			"Lat": 48.019573,
			"Lng": 66.923684,
			"Country": "Kazakhstan"
		},
		{
			"Code": "LA",
			"Lat": 19.85627,
			"Lng": 102.495496,
			"Country": "Laos"
		},
		{
			"Code": "LB",
			"Lat": 33.854721,
			"Lng": 35.862285,
			"Country": "Lebanon"
		},
		{
			"Code": "LC",
			"Lat": 13.909444,
			"Lng": -60.978893,
			"Country": "Saint Lucia"
		},
		{
			"Code": "LI",
			"Lat": 47.166,
			"Lng": 9.555373,
			"Country": "Liechtenstein"
		},
		{
			"Code": "LK",
			"Lat": 7.873054,
			"Lng": 80.771797,
			"Country": "Sri Lanka"
		},
		{
			"Code": "LR",
			"Lat": 6.428055,
			"Lng": -9.429499,
			"Country": "Liberia"
		},
		{
			"Code": "LS",
			"Lat": -29.609988,
			"Lng": 28.233608,
			"Country": "Lesotho"
		},
		{
			"Code": "LT",
			"Lat": 55.169438,
			"Lng": 23.881275,
			"Country": "Lithuania"
		},
		{
			"Code": "LU",
			"Lat": 49.815273,
			"Lng": 6.129583,
			"Country": "Luxembourg"
		},
		{
			"Code": "LV",
			"Lat": 56.879635,
			"Lng": 24.603189,
			"Country": "Latvia"
		},
		{
			"Code": "LY",
			"Lat": 26.3351,
			"Lng": 17.228331,
			"Country": "Libya"
		},
		{
			"Code": "MA",
			"Lat": 31.791702,
			"Lng": -7.09262,
			"Country": "Morocco"
		},
		{
			"Code": "MC",
			"Lat": 43.750298,
			"Lng": 7.412841,
			"Country": "Monaco"
		},
		{
			"Code": "MD",
			"Lat": 47.411631,
			"Lng": 28.369885,
			"Country": "Moldova"
		},
		{
			"Code": "ME",
			"Lat": 42.708678,
			"Lng": 19.37439,
			"Country": "Montenegro"
		},
		{
			"Code": "MG",
			"Lat": -18.766947,
			"Lng": 46.869107,
			"Country": "Madagascar"
		},
		{
			"Code": "MH",
			"Lat": 7.131474,
			"Lng": 171.184478,
			"Country": "Marshall Islands"
		},
		{
			"Code": "MK",
			"Lat": 41.608635,
			"Lng": 21.745275,
			"Country": "Macedonia [FYROM]"
		},
		{
			"Code": "ML",
			"Lat": 17.570692,
			"Lng": -3.996166,
			"Country": "Mali"
		},
		{
			"Code": "MM",
			"Lat": 21.913965,
			"Lng": 95.956223,
			"Country": "Myanmar [Burma]"
		},
		{
			"Code": "MN",
			"Lat": 46.862496,
			"Lng": 103.846656,
			"Country": "Mongolia"
		},
		{
			"Code": "MO",
			"Lat": 22.198745,
			"Lng": 113.543873,
			"Country": "Macau"
		},
		{
			"Code": "MP",
			"Lat": 17.33083,
			"Lng": 145.38469,
			"Country": "Northern Mariana Islands"
		},
		{
			"Code": "MQ",
			"Lat": 14.641528,
			"Lng": -61.024174,
			"Country": "Martinique"
		},
		{
			"Code": "MR",
			"Lat": 21.00789,
			"Lng": -10.940835,
			"Country": "Mauritania"
		},
		{
			"Code": "MS",
			"Lat": 16.742498,
			"Lng": -62.187366,
			"Country": "Montserrat"
		},
		{
			"Code": "MT",
			"Lat": 35.937496,
			"Lng": 14.375416,
			"Country": "Malta"
		},
		{
			"Code": "MU",
			"Lat": -20.348404,
			"Lng": 57.552152,
			"Country": "Mauritius"
		},
		{
			"Code": "MV",
			"Lat": 3.202778,
			"Lng": 73.22068,
			"Country": "Maldives"
		},
		{
			"Code": "MW",
			"Lat": -13.254308,
			"Lng": 34.301525,
			"Country": "Malawi"
		},
		{
			"Code": "MX",
			"Lat": 23.634501,
			"Lng": -102.552784,
			"Country": "Mexico"
		},
		{
			"Code": "MY",
			"Lat": 4.210484,
			"Lng": 101.975766,
			"Country": "Malaysia"
		},
		{
			"Code": "MZ",
			"Lat": -18.665695,
			"Lng": 35.529562,
			"Country": "Mozambique"
		},
		{
			"Code": "NA",
			"Lat": -22.95764,
			"Lng": 18.49041,
			"Country": "Namibia"
		},
		{
			"Code": "NC",
			"Lat": -20.904305,
			"Lng": 165.618042,
			"Country": "New Caledonia"
		},
		{
			"Code": "NE",
			"Lat": 17.607789,
			"Lng": 8.081666,
			"Country": "Niger"
		},
		{
			"Code": "NF",
			"Lat": -29.040835,
			"Lng": 167.954712,
			"Country": "Norfolk Island"
		},
		{
			"Code": "NG",
			"Lat": 9.081999,
			"Lng": 8.675277,
			"Country": "Nigeria"
		},
		{
			"Code": "NI",
			"Lat": 12.865416,
			"Lng": -85.207229,
			"Country": "Nicaragua"
		},
		{
			"Code": "NL",
			"Lat": 52.132633,
			"Lng": 5.291266,
			"Country": "Netherlands"
		},
		{
			"Code": "NO",
			"Lat": 60.472024,
			"Lng": 8.468946,
			"Country": "Norway"
		},
		{
			"Code": "NP",
			"Lat": 28.394857,
			"Lng": 84.124008,
			"Country": "Nepal"
		},
		{
			"Code": "NR",
			"Lat": -0.522778,
			"Lng": 166.931503,
			"Country": "Nauru"
		},
		{
			"Code": "NU",
			"Lat": -19.054445,
			"Lng": -169.867233,
			"Country": "Niue"
		},
		{
			"Code": "NZ",
			"Lat": -40.900557,
			"Lng": 174.885971,
			"Country": "New Zealand"
		},
		{
			"Code": "OM",
			"Lat": 21.512583,
			"Lng": 55.923255,
			"Country": "Oman"
		},
		{
			"Code": "PA",
			"Lat": 8.537981,
			"Lng": -80.782127,
			"Country": "Panama"
		},
		{
			"Code": "PE",
			"Lat": -9.189967,
			"Lng": -75.015152,
			"Country": "Peru"
		},
		{
			"Code": "PF",
			"Lat": -17.679742,
			"Lng": -149.406843,
			"Country": "French Polynesia"
		},
		{
			"Code": "PG",
			"Lat": -6.314993,
			"Lng": 143.95555,
			"Country": "Papua New Guinea"
		},
		{
			"Code": "PH",
			"Lat": 12.879721,
			"Lng": 121.774017,
			"Country": "Philippines"
		},
		{
			"Code": "PK",
			"Lat": 30.375321,
			"Lng": 69.345116,
			"Country": "Pakistan"
		},
		{
			"Code": "PL",
			"Lat": 51.919438,
			"Lng": 19.145136,
			"Country": "Poland"
		},
		{
			"Code": "PM",
			"Lat": 46.941936,
			"Lng": -56.27111,
			"Country": "Saint Pierre and Miquelon"
		},
		{
			"Code": "PN",
			"Lat": -24.703615,
			"Lng": -127.439308,
			"Country": "Pitcairn Islands"
		},
		{
			"Code": "PR",
			"Lat": 18.220833,
			"Lng": -66.590149,
			"Country": "Puerto Rico"
		},
		{
			"Code": "PS",
			"Lat": 31.952162,
			"Lng": 35.233154,
			"Country": "Palestinian Territories"
		},
		{
			"Code": "PT",
			"Lat": 39.399872,
			"Lng": -8.224454,
			"Country": "Portugal"
		},
		{
			"Code": "PW",
			"Lat": 7.51498,
			"Lng": 134.58252,
			"Country": "Palau"
		},
		{
			"Code": "PY",
			"Lat": -23.442503,
			"Lng": -58.443832,
			"Country": "Paraguay"
		},
		{
			"Code": "QA",
			"Lat": 25.354826,
			"Lng": 51.183884,
			"Country": "Qatar"
		},
		{
			"Code": "RE",
			"Lat": -21.115141,
			"Lng": 55.536384,
			"Country": "Réunion"
		},
		{
			"Code": "RO",
			"Lat": 45.943161,
			"Lng": 24.96676,
			"Country": "Romania"
		},
		{
			"Code": "RS",
			"Lat": 44.016521,
			"Lng": 21.005859,
			"Country": "Serbia"
		},
		{
			"Code": "RU",
			"Lat": 61.52401,
			"Lng": 105.318756,
			"Country": "Russia"
		},
		{
			"Code": "RW",
			"Lat": -1.940278,
			"Lng": 29.873888,
			"Country": "Rwanda"
		},
		{
			"Code": "SA",
			"Lat": 23.885942,
			"Lng": 45.079162,
			"Country": "Saudi Arabia"
		},
		{
			"Code": "SB",
			"Lat": -9.64571,
			"Lng": 160.156194,
			"Country": "Solomon Islands"
		},
		{
			"Code": "SC",
			"Lat": -4.679574,
			"Lng": 55.491977,
			"Country": "Seychelles"
		},
		{
			"Code": "SD",
			"Lat": 12.862807,
			"Lng": 30.217636,
			"Country": "Sudan"
		},
		{
			"Code": "SE",
			"Lat": 60.128161,
			"Lng": 18.643501,
			"Country": "Sweden"
		},
		{
			"Code": "SG",
			"Lat": 1.352083,
			"Lng": 103.819836,
			"Country": "Singapore"
		},
		{
			"Code": "SH",
			"Lat": -24.143474,
			"Lng": -10.030696,
			"Country": "Saint Helena"
		},
		{
			"Code": "SI",
			"Lat": 46.151241,
			"Lng": 14.995463,
			"Country": "Slovenia"
		},
		{
			"Code": "SJ",
			"Lat": 77.553604,
			"Lng": 23.670272,
			"Country": "Svalbard and Jan Mayen"
		},
		{
			"Code": "SK",
			"Lat": 48.669026,
			"Lng": 19.699024,
			"Country": "Slovakia"
		},
		{
			"Code": "SL",
			"Lat": 8.460555,
			"Lng": -11.779889,
			"Country": "Sierra Leone"
		},
		{
			"Code": "SM",
			"Lat": 43.94236,
			"Lng": 12.457777,
			"Country": "San Marino"
		},
		{
			"Code": "SN",
			"Lat": 14.497401,
			"Lng": -14.452362,
			"Country": "Senegal"
		},
		{
			"Code": "SO",
			"Lat": 5.152149,
			"Lng": 46.199616,
			"Country": "Somalia"
		},
		{
			"Code": "SR",
			"Lat": 3.919305,
			"Lng": -56.027783,
			"Country": "Suriname"
		},
		{
			"Code": "ST",
			"Lat": 0.18636,
			"Lng": 6.613081,
			"Country": "São Tomé and Príncipe"
		},
		{
			"Code": "SV",
			"Lat": 13.794185,
			"Lng": -88.89653,
			"Country": "El Salvador"
		},
		{
			"Code": "SY",
			"Lat": 34.802075,
			"Lng": 38.996815,
			"Country": "Syria"
		},
		{
			"Code": "SZ",
			"Lat": -26.522503,
			"Lng": 31.465866,
			"Country": "Swaziland"
		},
		{
			"Code": "TC",
			"Lat": 21.694025,
			"Lng": -71.797928,
			"Country": "Turks and Caicos Islands"
		},
		{
			"Code": "TD",
			"Lat": 15.454166,
			"Lng": 18.732207,
			"Country": "Chad"
		},
		{
			"Code": "TF",
			"Lat": -49.280366,
			"Lng": 69.348557,
			"Country": "French Southern Territories"
		},
		{
			"Code": "TG",
			"Lat": 8.619543,
			"Lng": 0.824782,
			"Country": "Togo"
		},
		{
			"Code": "TH",
			"Lat": 15.870032,
			"Lng": 100.992541,
			"Country": "Thailand"
		},
		{
			"Code": "TJ",
			"Lat": 38.861034,
			"Lng": 71.276093,
			"Country": "Tajikistan"
		},
		{
			"Code": "TK",
			"Lat": -8.967363,
			"Lng": -171.855881,
			"Country": "Tokelau"
		},
		{
			"Code": "TL",
			"Lat": -8.874217,
			"Lng": 125.727539,
			"Country": "Timor-Leste"
		},
		{
			"Code": "TM",
			"Lat": 38.969719,
			"Lng": 59.556278,
			"Country": "Turkmenistan"
		},
		{
			"Code": "TN",
			"Lat": 33.886917,
			"Lng": 9.537499,
			"Country": "Tunisia"
		},
		{
			"Code": "TO",
			"Lat": -21.178986,
			"Lng": -175.198242,
			"Country": "Tonga"
		},
		{
			"Code": "TR",
			"Lat": 38.963745,
			"Lng": 35.243322,
			"Country": "Turkey"
		},
		{
			"Code": "TT",
			"Lat": 10.691803,
			"Lng": -61.222503,
			"Country": "Trinidad and Tobago"
		},
		{
			"Code": "TV",
			"Lat": -7.109535,
			"Lng": 177.64933,
			"Country": "Tuvalu"
		},
		{
			"Code": "TW",
			"Lat": 23.69781,
			"Lng": 120.960515,
			"Country": "Taiwan"
		},
		{
			"Code": "TZ",
			"Lat": -6.369028,
			"Lng": 34.888822,
			"Country": "Tanzania"
		},
		{
			"Code": "UA",
			"Lat": 48.379433,
			"Lng": 31.16558,
			"Country": "Ukraine"
		},
		{
			"Code": "UG",
			"Lat": 1.373333,
			"Lng": 32.290275,
			"Country": "Uganda"
		},
		{
			"Code": "UM",
			"Lat": null,
			"Lng": null,
			"Country": "U.S. Minor Outlying Islands"
		},
		{
			"Code": "US",
			"Lat": 37.09024,
			"Lng": -95.712891,
			"Country": "United States"
		},
		{
			"Code": "UY",
			"Lat": -32.522779,
			"Lng": -55.765835,
			"Country": "Uruguay"
		},
		{
			"Code": "UZ",
			"Lat": 41.377491,
			"Lng": 64.585262,
			"Country": "Uzbekistan"
		},
		{
			"Code": "VA",
			"Lat": 41.902916,
			"Lng": 12.453389,
			"Country": "Vatican City"
		},
		{
			"Code": "VC",
			"Lat": 12.984305,
			"Lng": -61.287228,
			"Country": "Saint Vincent and the Grenadines"
		},
		{
			"Code": "VE",
			"Lat": 6.42375,
			"Lng": -66.58973,
			"Country": "Venezuela"
		},
		{
			"Code": "VG",
			"Lat": 18.420695,
			"Lng": -64.639968,
			"Country": "British Virgin Islands"
		},
		{
			"Code": "VI",
			"Lat": 18.335765,
			"Lng": -64.896335,
			"Country": "U.S. Virgin Islands"
		},
		{
			"Code": "VN",
			"Lat": 14.058324,
			"Lng": 108.277199,
			"Country": "Vietnam"
		},
		{
			"Code": "VU",
			"Lat": -15.376706,
			"Lng": 166.959158,
			"Country": "Vanuatu"
		},
		{
			"Code": "WF",
			"Lat": -13.768752,
			"Lng": -177.156097,
			"Country": "Wallis and Futuna"
		},
		{
			"Code": "WS",
			"Lat": -13.759029,
			"Lng": -172.104629,
			"Country": "Samoa"
		},
		{
			"Code": "XK",
			"Lat": 42.602636,
			"Lng": 20.902977,
			"Country": "Kosovo"
		},
		{
			"Code": "YE",
			"Lat": 15.552727,
			"Lng": 48.516388,
			"Country": "Yemen"
		},
		{
			"Code": "YT",
			"Lat": -12.8275,
			"Lng": 45.166244,
			"Country": "Mayotte"
		},
		{
			"Code": "ZA",
			"Lat": -30.559482,
			"Lng": 22.937506,
			"Country": "South Africa"
		},
		{
			"Code": "ZM",
			"Lat": -13.133897,
			"Lng": 27.849332,
			"Country": "Zambia"
		},
		{
			"Code": "ZW",
			"Lat": -19.015438,
			"Lng": 29.154857,
			"Country": "Zimbabwe"
		}
	];
}
