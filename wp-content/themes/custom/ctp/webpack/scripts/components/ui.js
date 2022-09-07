/**
 * Module: ui
 * General ui behaviors
 */

function init() {
	// Toggle active state
	$('[data-toggle-active]').on('click', function(e) {
		e.preventDefault();
		var target = $(this).data('toggle-active');
		$(this).toggleClass('active');
        $(target).toggleClass('active');
	});

  // Toggle vin lookup forms
  $('#btn-vin').on('click', function(e) {
    e.preventDefault();
    if (!$(this).hasClass('active')) {
		  $('#form-title .form-title').toggleClass('active');
      $('#formtoggle .form-toggle').toggleClass('active');
      $('#formsubmit').removeClass('method_plate');
      $('#formsubmit').addClass('method_vin');
      $('#formsubmit input.plate').hide().val("");
      $('#formsubmit #state-select').hide();
      $('#formsubmit #state-select .select-items div').removeClass("same-as-selected").removeAttr('class');;
      $('#formsubmit #state-select .select-selected').text("State").css("color", "#5e5e5e");
      $('#formsubmit #license-state-select').val("");
      $('#formsubmit input.vin').show();
    }
  });
  $('#btn-plate').on('click', function(e) {
    e.preventDefault();
    if (!$(this).hasClass('active')) {
      $('#formtoggle .form-toggle').toggleClass('active');
		  $('#form-title .form-title').toggleClass('active');
      $('#formsubmit').removeClass('method_vin');
      $('#formsubmit').addClass('method_plate');
      $('#formsubmit input.vin').hide().val("");
      $('#formsubmit #state-select').show();
      $('#formsubmit input.plate').show();
    }
  });

  // Help to fake placeholder functionality for custom select setup
  $('body').on('DOMSubtreeModified', '#formsubmit #state-select .select-selected', function(){
    $('#formsubmit #state-select .select-selected').css("color", "#000");
  });

  // Replace previous ability to preload state field with value from post
  $( document ).ready(function() {
      var statePost = $('#formsubmit #state-select').attr('data-state-id-post');

      if (statePost.length > 0) {
        $('#formsubmit #state-select .select-selected').text(statePost).css("color", "#000");
        $('#formsubmit #state-select .select-items div').each(function() {
          if ($(this).text() == statePost) {
            $(this).addClass("same-as-selected");
          }
        });
      }
  });

  // Smooth scroll
  $('.js-scroll[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
              $('html, body').animate({
                  scrollTop: target.offset().top
              }, 1000);
              return false;
          }
      }
  });

  // Toggle Location Display
  // $('.selected-select').on('click', function(e) {
  //     if ($(this).text() != "Location") {
  //         $('.location-content').show();
  //     }
  // });

  // Custom Select
  var x, i, j, l, ll, selElmnt, a, b, c;
  /* Look for any elements with the class "custom-select": */
  x = document.getElementsByClassName("custom-select");
  l = x.length;
  for (i = 0; i < l; i++) {
    selElmnt = x[i].getElementsByTagName("select")[0];
    ll = selElmnt.length;
    /* For each element, create a new DIV that will act as the selected item: */
    a = document.createElement("DIV");
    a.setAttribute("class", "select-selected");
    a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
    x[i].appendChild(a);
    /* For each element, create a new DIV that will contain the option list: */
    b = document.createElement("DIV");
    b.setAttribute("class", "select-items select-hide");
    for (j = 1; j < ll; j++) {
      /* For each option in the original select element,
      create a new DIV that will act as an option item: */
      c = document.createElement("DIV");
      c.innerHTML = selElmnt.options[j].innerHTML;
      c.addEventListener("click", function(e) {
          /* When an item is clicked, update the original select box,
          and the selected item: */
          var y, i, k, s, h, sl, yl;
          s = this.parentNode.parentNode.getElementsByTagName("select")[0];
          sl = s.length;
          h = this.parentNode.previousSibling;
          for (i = 0; i < sl; i++) {
            if (s.options[i].innerHTML == this.innerHTML) {
            	  showRepairLocations(this.innerHTML);
			  s.selectedIndex = i;
              h.innerHTML = this.innerHTML;
              y = this.parentNode.getElementsByClassName("same-as-selected");
              yl = y.length;
              for (k = 0; k < yl; k++) {
                y[k].removeAttribute("class");
              }
              this.setAttribute("class", "same-as-selected");
              break;
            }
          }
          h.click();
      });
      b.appendChild(c);
    }
    x[i].appendChild(b);
    a.addEventListener("click", function(e) {
      /* When the select box is clicked, close any other select boxes,
      and open/close the current select box: */
      e.stopPropagation();
      closeAllSelect(this);
      this.nextSibling.classList.toggle("select-hide");
      this.classList.toggle("select-arrow-active");
    });
  }

  function closeAllSelect(elmnt) {
    /* A function that will close all select boxes in the document,
    except the current select box: */
    var x, y, i, xl, yl, arrNo = [];
    x = document.getElementsByClassName("select-items");
    y = document.getElementsByClassName("select-selected");
    xl = x.length;
    yl = y.length;
    for (i = 0; i < yl; i++) {
      if (elmnt == y[i]) {
        arrNo.push(i)
      } else {
        y[i].classList.remove("select-arrow-active");
      }
    }
    for (i = 0; i < xl; i++) {
      if (arrNo.indexOf(i)) {
        x[i].classList.add("select-hide");
      }
    }
  }

  /* If the user clicks anywhere outside the select box,
  then close all select boxes: */
  document.addEventListener("click", closeAllSelect);


	// Ajax to show location content on bulk vin tool
	function showRepairLocations(city) {
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: urls.ajax,
			data: {
				action: "get_repair_locations_ajax",
				city: city
			},
			success: function(response) {
				$('.locations').html(response);
				$('.location-content').css('display', 'block');
			}

		});
	}

}

/**
 * Public API
 * @type {Object}
 */
module.exports = {
	init: init
};

