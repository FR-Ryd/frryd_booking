config = {
	siteUrl: "http://" + window.location.hostname.toString() +"/" //http://booking.frryd.se/"
};

if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function(elt) {
    var len = this.length;

    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;

    for (; from < len; from++) {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}


(function($) {

	$.fn.togglable = function(settings) {
		var config = {'toggleMethod': 'slide'}; // slide or hide

		if (settings) $.extend(config, settings);

		this.each(function() {
			var toggleButton = $(this).find(".toggleButton").first();
			var toggleContent = $(this).find(".toggleContent").first();
			toggleContent.hide();
			toggleButton.css("cursor", "pointer").toggle(function() {
				$(this).toggleClass("expanded");
				if (config.toggleMethod == 'hide') {
					toggleContent.show();
				} else {
					toggleContent.slideDown();
				}
			}, function() {
				$(this).toggleClass("expanded");
				if (config.toggleMethod == 'hide') {
					toggleContent.hide();
				} else {
					toggleContent.slideUp();
				}
			});
		});
	};

	$.fn.calendarSessionAdmin = function(settings) {
		// session choosing calendar

		this.each(function() {

			var emptyDateCells = $(this).find("td.no_lending_session");
			var sessionDateCells = $(this).find("td.lending_session");
			var prevMonthButton = $(this).find(".prevMonthButton");
			var nextMonthButton = $(this).find(".nextMonthButton");
			var calendarDiv = $(this).find(".calendarSessionContent");

            emptyDateCells.live("click", function() {
				// free dates
				var date = $(this).find(".date").val();

				if (confirm("Do you want to add a session here, " + date + "?")) {
					var form = $("<form action='session.php' method='post'><input type='hidden' name='date' value='"+date+"' /><input type='hidden' name='create_session' value='yes' /></form>");
					form.appendTo($("body")).submit();
				} else {

				}
			});

			sessionDateCells.live("click", function() {
				// datum med pass
				var link = $(this).find("a").attr("href");
				document.location = link;
			});

			nextMonthButton.live("click", function() {
				var date = $(this).find(".currentDate").val();
				var url = config.siteUrl + "session.php?ajax=1&nextMonth=1&date="+date;
				$.get(url, function(data){
					calendarDiv.html(data);
				});
			});
			prevMonthButton.live("click", function() {
				var date = $(this).find(".currentDate").val();
				var url = config.siteUrl + "session.php?ajax=1&prevMonth=1&date="+date;
				$.get(url, function(data){
					calendarDiv.html(data);
				});
			});

		});

	};

	$.fn.bookingFormCalendar = function(settings) {
		// new calendar version

		this.each(function() {

			var itemID = parseInt($(this).find(".bookingItemID").val());
			var maxPeriods = parseInt($(this).find(".maxLendingPeriods").val());

			var numItemsSelector = $(this).children(".numItemsSelector");

			var prevMonthButton = $(this).find(".prevMonthButton");
			var nextMonthButton = $(this).find(".nextMonthButton");
			var calendarBookingDiv = $(this).find(".calendar");

			var bookedPeriods = [];
			var bookedPeriodNext = [];
			var numBooked = 0;

			// for each calendar table

            var expanded = false;
            $(this).find(".firstLoadButton").click(function() {
            if (!expanded) {
                // load calendar first, with ajax
                var now = new Date();
                var date = ((now.getYear() < 1000) ? now.getYear() + 1900 : now.getYear()) + "-" + (((now.getMonth() + 1) < 10) ? '0' + (now.getMonth() + 1) : (now.    getMonth() + 1)) + "-" + ((now.getDate() < 10) ? '0' + now.getDate() : now.getDate());
                var url = config.siteUrl + "index.php?ajax=1&item="+itemID+"&date="+date;
                var loadButton = $(this);
                $.get(url, function(data){
                    loadButton.remove();
                    calendarBookingDiv.html(data);
                    dates = calendarBookingDiv.find("td.date.period");
                    book();
                });

                expanded = true;
                $(this).html("Loading ...");
            }
            return false;
            });

			var dates = $(this).find("td.date.period");


			nextMonthButton.live("click", function() {
				var date = $(this).find(".currentDate").val();
				var itemID = $(this).find(".itemID").val();
				var url = config.siteUrl + "index.php?ajax=1&item="+itemID+"&nextMonth=1&date="+date;
				$.get(url, function(data){
					calendarBookingDiv.html(data);
					dates = calendarBookingDiv.find("td.date.period");
					book();

				});
			});
			prevMonthButton.live("click", function() {
				var date = $(this).find(".currentDate").val();
				var itemID = $(this).find(".itemID").val();
				var url = config.siteUrl + "index.php?ajax=1&item="+itemID+"&prevMonth=1&date="+date;
				$.get(url, function(data){
					calendarBookingDiv.html(data);
					dates = calendarBookingDiv.find("td.date.period");
					book();

				});
			});


			var bookClick = function () {
				var sessionDate = parseInt($(this).find(".sessionDate").val().split("-").join(""));
				var prevSessionDate = parseInt($(this).find(".prevSessionDate").val().split("-").join(""));
				var nextSessionDate = parseInt($(this).find(".nextSessionDate").val().split("-").join(""));
				var numFree = parseInt($(this).children(".numFree").val());

				if (!bookedPeriods.length) {
					// first click

					numBooked = numItemsSelector.val();
				}
				if (numFree < numBooked) {
					alert("Unfortunately there are only "+numFree+" items available this period.\nYou have selected "+numBooked+" items.");
					numItemsSelector.val(numFree);
					numBooked = numFree;
				}
				numItemsSelector.children().each(function(i, selected){
					if ($(selected).val() > numFree) {

					}
				});

				bookedPeriods.push(sessionDate);
                bookedPeriodNext[nextSessionDate] = true;

				book();

				if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).size()) {
					// append to existing item form
					var index = parseInt($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".index").val());
					$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).append(
						"<input type=\"text\" class=\"sessionInput\" name=\"item["+index+"][sessions][]\" value=\""+sessionDate+"\" />");

				} else {
					var index = $("#cartForm").find("#cartFormFieldset").find(".cartFormFieldsetItem").size();
					$("#cartForm").find("#cartFormFieldset").append("<fieldset class=\"cartFormFieldsetItem\" id=\"cartFormFieldsetItem_"+itemID+"\">\
						<legend>Item "+itemID+"</legend>\
						Index: <input type=\"text\" class=\"index\" name=\"index\" value=\""+index+"\" />\
						ID: <input type=\"text\" name=\"item["+index+"][id]\" value=\""+itemID+"\" />\
						Antal: <input type=\"text\" class=\"num\" name=\"item["+index+"][num]\" value=\""+numBooked+"\" /><br />\
						Sessions:\
						<input type=\"text\" class=\"sessionInput\" name=\"item["+index+"][sessions][]\" value=\""+sessionDate+"\" />\
					</fieldset>");
					$("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html((parseInt($("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html()) + parseInt(numBooked)));
				}
			};

			var unbookClick = function () {
				var sessionDate = parseInt($(this).children(".sessionDate").val().split("-").join(""));
				var nextSessionDate = parseInt($(this).find(".nextSessionDate").val().split("-").join(""));
				bookedPeriods.splice(jQuery.inArray(sessionDate, bookedPeriods), 1);

                bookedPeriodNext[nextSessionDate] = false;

				book();

				if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).size()) {

					$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".sessionInput[value='"+sessionDate+"']").remove();

					if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".sessionInput").size() == 0) {
						$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).remove();

						$("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html((parseInt($("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html()) - parseInt(numBooked)));
					}
				}
			};

			var book = function() {
				// reset the number of items selector
				numItemsSelector.children().each(function(i, selected){
					$(this).removeAttr("disabled").css("color","black").html($(this).attr("value"));
				});

				if (bookedPeriods.length) {
                    //The user has tried to book something.
                    //Find earliest and latest booking done.
					if (bookedPeriods.length <= maxPeriods) {
                        var smallest = bookedPeriods[0];
                        var largest = bookedPeriods[0];
                        for(var i = 0; i < bookedPeriods.length; i++) {
                            if (bookedPeriods[i] < smallest) {
                                smallest = bookedPeriods[i];
                            } else if (bookedPeriods[i] > largest) {
                                largest = bookedPeriods[i];
                            }
                        }
					}


					var checkedPeriods = Array(); // to keep track of unique periods

                    //For each date, ?
					dates.filter(".available, .prevAvailable").each(function() {

						var sessionDate = parseInt($(this).find(".sessionDate").val().split("-").join(""));
						var prevSessionDate = parseInt($(this).find(".prevSessionDate").val().split("-").join(""));
						var nextSessionDate = parseInt($(this).find(".nextSessionDate").val().split("-").join(""));

						var numFree = parseInt($(this).children(".numFree").val());
						var prevFree = parseInt($(this).children(".prevFree").val());

						$(this).unbind("click");

                        //If this date is booked
						if (jQuery.inArray(sessionDate, bookedPeriods) != -1) {

                            //If this date is in the checkedPeriods already
							if (jQuery.inArray(sessionDate, checkedPeriods) != -1) {
								// update the number of items selector
								numItemsSelector.children().each(function(i, selected){
									if ($(this).val() > numFree && $(this).attr("disabled") != true) {
										$(this).attr("disabled","disabled").css("color","gray").html($(this).html() + "");
									}
								});
							}

							$(this).addClass("booked").removeClass("bookable");

							//TODO clean up code as described below
                            //This date is the first and last date, ie.. the only date!
                            // Make it unbookable
                            //these if's are bad
							if (sessionDate == smallest && sessionDate == largest) {
								// an already booked session, on both edges
								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

                            //This is the first booked date
							} else if (sessionDate == smallest) {
								// an already booked session, on the left edge

								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

							} else if (sessionDate == largest) {
								// an already booked session, on the right edge

								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

							} else {
								// an already booked session in the middle

                                //We can't unbook sessions that are in the middle of other booked sessions, which makes sense.
								$(this).removeClass("unbookable");

							}

                        // If the date is not among the dates we have booked for..
                        //   bookedPeriods.length < maxPeriods ===> we have not used up all our booking-spree-allowance :P
                        //   numFree >= numBooked ===> there is more stuff to book
                        //   sortid ===> it lies next to a booked date
                        //       ===> Make things bookable

                        //TODO: sortID
						} else if (bookedPeriods.length < maxPeriods && numFree >= numBooked && (prevSessionDate == largest || nextSessionDate == smallest) ) {
							// an adjacent session

							$(this).bind("click.book", bookClick);
							$(this).removeClass("booked").removeClass("unbookable").addClass("bookable");

                        //Either we have booked as many as we can, we have booked for as long as we can, or this session is too far from our booked sessions.
						} else {
							// a distant session

							$(this).removeClass("booked").removeClass("unbookable").removeClass("bookable");
						}

						// Check the same for previous
						if (jQuery.inArray(prevSessionDate, bookedPeriods) != -1) {
							// the previous is an already booked session
							$(this).addClass("prevBooked");

                        	//TODO: sortid

						} else if (bookedPeriods.length < maxPeriods && prevFree >= numBooked && (sessionDate == smallest || bookedPeriodNext[prevSessionDate])) {
							// the previous is an adjacent session
							$(this).removeClass("prevBooked").addClass("prevBookable");
						} else {
							// the previous is a distant session
							$(this).removeClass("prevBooked").removeClass("prevBookable");
						}

						checkedPeriods.push(sessionDate);

					});
				} else {
					// an availiable session, since none is booked

					dates.filter(".available, .prevAvailable").each(function() {
						var numFree = parseInt($(this).children(".numFree").val());
						if (numFree) {
							$(this).unbind("click");
							$(this).bind("click.book", bookClick);
							$(this).removeClass("booked").removeClass("unbookable").addClass("bookable").removeClass("prevBooked").addClass("prevBookable");
						} else {
							// not available
							$(this).removeClass("prevBooked").addClass("prevBookable");
						}
					});
				}
			};

			dates.filter(".available").bind("click.book", bookClick);

			numItemsSelector.change(function() {
				numBooked = $(this).val();
				// update the form:
				if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).size()) {
					// update num items label: (remove old value for this item, add new)
					$("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html((parseInt($("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html())
						- parseInt($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".num").val())
						+ parseInt(numBooked)));

					$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".num").val(numBooked);
				}
				book();
			});

		});

	};

	$.fn.bookingFormItem = function(settings) {
		this.each(function() {
			var itemID = parseInt($(this).children(".bookingItemID").val());
			var maxPeriods = parseInt($(this).children(".maxLendingPeriods").val());
			var periods = $(this).find(".itemBookingPeriod").filter(".available");

			var bookedPeriods = [];
			var bookedPeriodSorts = [];
			var numBooked = 0;

			var content = $(this).children(".itemContent").hide();

			var title = $(this).children(".itemHeading");

			var toggled = false;
			var clickFunc = function(){
				if(toggled){
					title.toggleClass("expanded");
					content.slideUp();
					toggled = false;
				}
				else{
					title.toggleClass("expanded");
					content.slideDown();
					toggled = true;
				}
			}

			var startFunc = function() {
				title.toggleClass("expanded");
				content.slideDown();
			};
			var endFunc = function() {
				title.toggleClass("expanded");
				content.slideUp();
			};

			title.css("cursor", "pointer").click(clickFunc);
			$(this).children(".itemImage").css("cursor", "pointer").click(clickFunc);
			//title.css("cursor", "pointer").toggle(startFunc, endFunc);
			//$(this).children(".itemImage").css("cursor", "pointer").toggle(startFunc, endFunc);

			var book = function () {
				//Update the periods look and functionality

				if (bookedPeriodSorts.length) {
					if (bookedPeriodSorts.length <= maxPeriods) {
						if (bookedPeriodSorts.length) {
							var smallest = bookedPeriodSorts[0];
							var largest = bookedPeriodSorts[0];
							for(var i = 0; i < bookedPeriodSorts.length; i++) {
								if (bookedPeriodSorts[i] < smallest) {
									smallest = bookedPeriodSorts[i];
								} else if (bookedPeriodSorts[i] > largest) {
									largest = bookedPeriodSorts[i];
								}
							}
						}
					}

					periods.each(function() {
						var sessionDate = parseInt($(this).children(".sessionDate").val().split("-").join(""));
						var numFree = parseInt($(this).children(".numFree").val());

						$(this).unbind("click");

						if (jQuery.inArray(sessionDate, bookedPeriodSorts) != -1) {
							// an already booked session

							$(this).addClass("booked").removeClass("bookable");

							if (sortID == smallest && sortID == largest) {
								// an already booked session, on both edges

								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

							} else if (sortID == smallest) {
								// an already booked session, on the left edge

								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

							} else if (sortID == largest) {
								// an already booked session, on the right edge

								$(this).bind("click.unbook", unbookClick);
								$(this).addClass("unbookable");

							} else {
								// an already booked session in the middle

								$(this).removeClass("unbookable");

							}
						} else if (bookedPeriodSorts.length < maxPeriods && numFree >= numBooked && (sortID == smallest - 1 || sortID == largest + 1)) {
							// an adjacent session

							$(this).bind("click.book", bookClick);
							$(this).removeClass("booked").removeClass("unbookable").addClass("bookable");

						} else {
							// a distant session

							$(this).removeClass("booked").removeClass("unbookable").removeClass("bookable");

						}
					});

				} else {
					// an availiable session, since none is booked

					periods.each(function() {
						$(this).unbind("click");
						$(this).bind("click.book", bookClick);
						$(this).removeClass("booked").removeClass("unbookable").addClass("bookable");
					});
				}

			}

			var bookClick = function () {

				var sessionDate = parseInt($(this).children(".sessionDate").val().split("-").join(""));
				var numFree = parseInt($(this).children(".numFree").val());

				if (!bookedPeriods.length) {
					numBooked = 1;
				}
				bookedPeriods.push(sessionDate);

				book();

				if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).size()) {
					// append to existing item form
					var index = parseInt($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".index").val());
					$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).append(
						"<input type=\"text\" class=\"sessionInput\" name=\"item["+index+"][sessions][]\" value=\""+sessionDate+"\" />");

				} else {
					// append new item form
					var index = $("#cartForm").find("#cartFormFieldset").find(".cartFormFieldsetItem").size();
					$("#cartForm").find("#cartFormFieldset").append("<fieldset class=\"cartFormFieldsetItem\" id=\"cartFormFieldsetItem_"+itemID+"\">\
						<legend>Item "+itemID+"</legend>\
						Index: <input type=\"text\" class=\"index\" name=\"index\" value=\""+index+"\" />\
						ID: <input type=\"text\" name=\"item["+index+"][id]\" value=\""+itemID+"\" />\
						Antal: <input type=\"text\" name=\"item["+index+"][num]\" value=\""+numBooked+"\" /><br />\
						Sessions:\
						<input type=\"text\" class=\"sessionInput\" name=\"item["+index+"][sessions][]\" value=\""+sessionDate+"\" />\
					</fieldset>");
					$("#cartForm").find("#checkoutFieldset").find("legend").html((index + 1) +" föremål bokade");
				}
			};

			var unbookClick = function () {
				var sessionDate = parseInt($(this).children(".sessionDate").val().split("-").join(""));
				bookedPeriods.splice(jQuery.inArray(sessionDate, bookedPeriods), 1);

				book();

				if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).size()) {

					$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".sessionInput[value='"+sessionDate+"']").remove();

					if ($("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).find(".sessionInput").size() == 0) {
						$("#cartForm").find("#cartFormFieldset").find("#cartFormFieldsetItem_"+itemID).remove();

						$("#cartForm").find("#checkoutFieldset").find("legend").find(".numSpan").html(($("#cartForm").find("#cartFormFieldset").find(".cartFormFieldsetItem").size()));
					}
				}
			};

			periods.bind("click.book", bookClick);

		});
		return this;

	};

})(jQuery);

//Calculate and display/hide empty bras between needed items on the front/booking page
var displayItemBars = function(){
	var containerWidth = parseInt($(".item_presentation").css("width"));
	var itemsInRow = Math.floor(containerWidth/299);

	//If item can't really fit put divider between every one
	if(itemsInRow == 0){
		itemsInRow = 1;
	}

	//Show every itemsInRow:th separator
	$(".categoryContainer").each(function(catIndex){
		$(this).children(".categoryItemHolder").children(".itemRowSeparator").each(function(index){
			//alert(index);
			if(((index+1) % itemsInRow == 0) && (index != 0)){
				$(this).show();
			}
			else{
				$(this).hide();
			}
		});
	});
	//alert("done");
}

$(document).ready(function () {

	var bookedItems = [];
	var bookedPeriods = [];

	$(".bookingFormItem").bookingFormItem();

	$(".calendarBooking").bookingFormCalendar();

	$(".calendarSessions").calendarSessionAdmin();

	$(".confirmBooking").click(function() {
		if ($(".accept_eula").is(':checked')) {
			return true;
		} else {
			$(".euladiv").css("border-left", "10px solid red").css("padding-left", "3px");
			return false;
		}
	});

	$(".togglable").togglable();
	$(".togglableRow").togglable({'toggleMethod': 'hide'});

	$("#checkout2").click(function() {

	});

	$(".getUserInfo").click(function() {
        var liu_id = $("#addUserEmail").val();
        var url = config.siteUrl + "booking.php?ajax=1&liu_id="+liu_id;
        //var loadButton = $(this);

        $(".userRemarkSection").empty();

        $.get(url, function(data){
            data = data.substring(data.indexOf("{"));
            obj = JSON.parse(data);
            $("#addUserName").val(obj.name);
            $("#addUserPhone").val(obj.phone);
            $("#addUserNIN").val(obj.nin);
            $("#addUserAddress").val(obj.address);
            for(var i = 0; i < obj.remarks.length; i++) {
                var remark = obj.remarks[i];
                var date = remark.date;
                var comment = remark.comment;
                $(".userRemarkSection").append( "<textarea disabled cols = '60' rows='4'>"+date+":\n"+comment+"</textarea><br>");
            }
        });
    });


	//Category expanding
	$(".categoryContainer").each(function(){
		var itemHolder = $(this).children(".categoryItemHolder");



		$(this).children(".rubrikBooking").toggle(function(){
				$(this).children(".categoryExpandImg").hide();
				$(this).children(".categoryContractImg").show();
				itemHolder.slideDown();
			},function(){
				$(this).children(".categoryExpandImg").show();
				$(this).children(".categoryContractImg").hide();
				$(this).toggleClass("expanded");
				itemHolder.slideUp();
			}
		);
	});

	//Rebalance bars for items
	displayItemBars();
	$(window).on('resize', displayItemBars); //Reorder on resize
});
