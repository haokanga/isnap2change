$(document).ready(function () {
    // GTM tracking.
    gtmDataLayer.push({
        'tool': 'standard-drink',
        'event': 'initialise',
    });

    // hide the tool on load as the intro copy needs to be show.
    $('#standard-drink-tool-wrapper').hide();

    // when you click on  the begin button it will hide the intro copy and load the tool
    $('.begin-sd-tool').on("click", function () {
        $('.standard-drinks-tool-intro').hide();
        $('#standard-drink-tool-wrapper').fadeIn(function () {
            $('.flexslider').flexslider({
                animation: "slide",
                animationLoop: false,
                touch: true,
                itemWidth: 153,
                itemMargin: 10,
                slideshow: false,
                before: function () {
                    $('.flexslider .slides').removeClass('end');
                },
                end: function () {
                    $('.flexslider .slides').addClass('end');
                }

            });
        });
        $('.sub-head-italic').text('Choose your container:');

        // GTM tracking.
        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': 'start',
            'eventValue': 0
        });

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': 'advance',
            'eventValue': 1
        });
    });


    var ratio = 1;
    var isMobile = false;

    // sets/resets all the values
    function getScreenSize() {
        var contentWidth = $(document).width();

        // Smartphones (portrait and landscape)
        if (contentWidth <= 480) {
            //alert('MOBILE');
            ratio = 0.75;
            isMobile = true;
        } else if (contentWidth > 480 && contentWidth <= 768) {
            //alert('TABLET');
            ratio = 1;
        } else {
            //alert('DESKTOP');
            ratio = 1;
        }

    }

    getScreenSize();


    //$('#standard-drink-tool-wrapper').css("width", (266 * ratio) + "px");
    //$('.drink-mask').css("width", (197 * ratio) + "px");
    //$('.maskimage').css("width", (140 * ratio) + "px");
    //$('.drink-overflow').css("width", (140 * ratio) + "px");

    // instantiate drink figures

    var filledStandardDrink = '';

    // holds all information
    var dataArray;

    // if set to true the SD will be updated while the person drags the levels. If false it will only update upon pressing guess button
    var dynamicStandardDrink = false;

    // how much % is the glass filled
    var pourLevelPercent;

    //  y axis from top of container
    var pourLevel;

    // how many mils are filled
    var filledMl;

    // Name of the Glass
    var glassTitle = '';
    // How much it holds in ml
    var glassCapacity = 0;
    // alcohol percentage
    var alcoholVol = 0;
    // number of standard drinks
    var numStandardDrinks = 0;

    // we round this value to closest 5ml
    var filledMlRounded;

    // how many ml in stardard
    var standardDrinkMl = 0;
    // this is the padding top. (number of pixels from the top of the image to where the vessel starts)
    var topPadding = 0;
    // This is in case of drinks like beers that have froth. It changes the calculation as the drink should be measured form the liquid not the froth.
    var froth = 0;
    // height of the container image
    var imageHeight = 0;
    // hight of only the fillable part of the vessel
    var fillableHeight = 0;

    // index of the data object ie. what drinking glass/bottle was selected
    var drinkGlassIndex = 0;
    // index of the drink object within the drinkglass (ie. data) object ie. what drinking drink was selected
    var drinkTypeIndex = 0;
    // Name of the liquid
    var drinkName = '';
    // More info copy
    var moreInfo = '';

    // this is the default calculation using the Round method (closest intiger). Later it can be overwritten to use float or ceil in order to fix certain liquid type calculations
    var calcStandardDrinks = function (serveml, alcoholvol) {
        return Math.round(((serveml / 1000) * alcoholvol * 0.789) * 10) / 10;
    };

    var isWorkplace = window.location.pathname.toLowerCase().indexOf("workplace") >= 0;

    var riskAssessmentLink = "";

    if (isWorkplace) {
        riskAssessmentLink = "/Alcohol-Your-Community/Alcohol-the-Workplace/Risk-Assessment-Tool";
    } else {
        riskAssessmentLink = "/Alcohol-Your-Health/Risk-Assessment-Tool";
    }

    var infoArray = [
		{
		    "Wine": [{
		        "bodyCopy": '<p>In Australia, a standard drink is any drink containing 10 grams of alcohol, regardless of container size or alcohol type (e.g beer, wine, spirit).</p><p>In Western Australia there are a wide selection of wine brands and varieties available to purchase, each with different percentages of alcohol volume.</p><p>An average restaurant serving of wine is 150mL. This means a 150mL average restaurant serving of white wine (11.5% Alc. Vol) equals approximately 1.4 standard drinks. A 150ml average restaurant serving of red wine (13% Alc. Vol) equals approximately 1.5 standard drinks. <sup>1</sup></p><p>To see if you are drinking to stay at low risk, complete our <a href="' + riskAssessmentLink + '">risk assessment tool</a>.</p><div class="references-container"><div class="references-button"><div class="references-icon"></div>References</div><div class="references-content"><p><sup>1</sup> Standard drinks guide. Retrieved from: <a href="http://www.alcohol.gov.au/internet/alcohol/publishing.nsf/Content/drinksguide-cnt" target="_blank">http://www.alcohol.gov.au/internet/alcohol/publishing.nsf/Content/drinksguide-cnt</a></p></div></div></div>'
		    }],
		    "Beer": [{
		        "bodyCopy": '<p>In Australia, a standard drink is any drink containing 10 grams of alcohol, regardless of container size or alcohol type (e.g beer, wine, spirit).</p><p>In Western Australia beer is sold in many different shaped and sized glasses. There is also a wide selection of beer brands and brews available to purchase, each containing different percentages of alcohol volume.</p><p>When selecting a beer, ask the staff its percentage of alcohol volume. The National Health and Medical Research Council (NHMRC) use this guide to determine the strength of the beer: <sup>1</sup></p><div class="breakout-box breakout-box-grey"><ul><li><strong>full strength</strong> beer is 4.8% Alc. Vol.</li><li><strong>mid-strength</strong> is 3.5% Alc. Vol.</li><li><strong>low strength</strong> is 2.7% Alc Vol.</li></ul></div><p>To see if you are drinking to stay at low risk, complete our <a href="' + riskAssessmentLink + '">risk assessment tool</a>.</p><div class="references-container"><div class="references-button"><div class="references-icon"></div>References</div><div class="references-content"><p><sup>1</sup> National Health and Medical Research Council. (2009). Australian guidelines to reduce health risks from drinking alcohol: Commonwealth of Australia. Available at <a href="http://www.nhmrc.gov.au/guidelines/publications/ds10" target="_blank">http://www.nhmrc.gov.au/guidelines/publications/ds10</a></p></div></div></div>'
		    }],
		    "Spirits": [{
		        "bodyCopy": '<p>In Australia, a standard drink is any drink containing 10 grams of alcohol, regardless of container size or alcohol type (e.g beer, wine, spirit).</p><p>In Australia, the sale of brandy (including cognac and armagnac), gin, rum, vodka or whisky must be made using an approved spirit measure. This ensures an accurate pour in capacities of 15 mL, 30 mL or 60mL. These measurement requirements must be followed when served with or without a mixer such as soft drink (eg vodka and orange, rum and cola), milk or water. <sup>1</sup></p><p>On their label, ready to drink (RTD) bottles and cans include how many standard drinks they contain.</p><p>To see if you are drinking to stay at low risk, complete our <a href="' + riskAssessmentLink + '">risk assessment tool</a>.</p><div class="references-container"><div class="references-button"><div class="references-icon"></div>References</div><div class="references-content"><p><sup>1</sup> Australian Government - National Measurement Institute Trade Measurement. Guide to the sale of alcohol. [Brochure] Retrieved from:  <a href="http://www.measurement.gov.au/Publications/trademeasurement/Documents/Guide%20to%20the%20Sale%20of%20Alcohol.pdf " target="_blank">http://www.measurement.gov.au/Publications/trademeasurement/Documents/Guide%20to%20the%20Sale%20of%20Alcohol.pdf </a></p></div></div></div>'
		    }]

		}];
    var drinks = [
		{
		    "WineSmall": [{
		        "typename": "White Wine",
		        "volume": 11.5,
		        "img": "liquid-white-wine-small.png",
		        "froth": 0
		    }, {
		        "typename": "Red Wine",
		        "volume": 13,
		        "img": "liquid-red-wine-small.png",
		        "froth": 0
		    }],
		    "WineLarge": [{
		        "typename": "White Wine",
		        "volume": 11.5,
		        "img": "liquid-white-wine-large.png",
		        "froth": 5
		    }, {
		        "typename": "Red Wine",
		        "volume": 13,
		        "img": "liquid-red-wine-large.png",
		        "froth": 5
		    }],
		    "Port": [{
		        "typename": "Fortified Wine",
		        "volume": 17.5,
		        "img": "liquid-fortified-wine-small.png"
		    }],
		    "WineBottle": [{
		        "typename": "White Wine",
		        "volume": 11.5,
		        "img": "liquid-white-wine-bottle.png",
		        "froth": 104
		    }, {
		        "typename": "Red Wine",
		        "volume": 13,
		        "img": "liquid-red-wine-bottle.png",
		        "froth": 104
		    }],
		    "SparklingWineGlass": [{
		        "typename": "Sparking Wine",
		        "volume": 12,
		        "img": "liquid-sparkling-wine-glass.png",
		        "froth": 13
		    }],
		    "FortifiedWineGlass": [{
		        "typename": "Spirits",
		        "volume": 17.5,
		        "img": "liquid-fortified-wine-fortified-glass.png"
		    }],
		    "Spirits": [{
		        "typename": "Spirits",
		        "volume": 40,
		        "img": "liquid-shot-glass.png",
		        "froth": 12,
		        "roundMethod": 'ceil'
		    }],
		    "Whisky": [{
		        "typename": "Whisky",
		        "volume": 40,
		        "img": "liquid-whiskey.png",
		        "froth": 22,
		        "roundMethod": 'ceil'
		    }],
		    "beerPint": [{
		        "typename": "Low Strength Beer",
		        "volume": 2.7,
		        "img": "liquid-beer-pint.png",
		        "froth": 51
		    }, {
		        "typename": "Mid Strength Beer",
		        "volume": 3.5,
		        "img": "liquid-beer-pint.png",
		        "froth": 51
		    }, {
		        "typename": "Full Strength Beer",
		        "volume": 4.8,
		        "img": "liquid-beer-pint.png",
		        "froth": 51
		    }],
		    "beerMiddy": [{
		        "typename": "Low Strength Beer",
		        "volume": 2.7,
		        "img": "liquid-beer-middy.png",
		        "froth": 37
		    }, {
		        "typename": "Mid Strength Beer",
		        "volume": 3.5,
		        "img": "liquid-beer-middy.png",
		        "froth": 37
		    }, {
		        "typename": "Full Strength Beer",
		        "volume": 4.8,
		        "img": "liquid-beer-middy.png",
		        "froth": 37
		    }],
		    "beerSchooner": [{
		        "typename": "Low Strength Beer",
		        "volume": 2.7,
		        "img": "liquid-beer-pint.png",
		        "froth": 47
		    }, {
		        "typename": "Mid Strength Beer",
		        "volume": 3.5,
		        "img": "liquid-beer-pint.png",
		        "froth": 47
		    }, {
		        "typename": "Full Strength Beer",
		        "volume": 4.8,
		        "img": "liquid-beer-pint.png",
		        "froth": 47
		    }]
		}
    ];

    var dataArray = [
		{
		    "title": "Large Wine Glass",
		    "img": "mask-large-wine-glass.png",
		    "thumb": "thumb-large-wine-glass.jpg",
		    "imgHeight": 363,
		    "fillableheight": 177,
		    "toppadding": 0,
		    "capacity": 350,
		    "drinks": drinks[0].WineLarge,
		    "info": infoArray[0].Wine
		},
		{
		    "title": "Small Wine Glass",
		    "img": "mask-small-wine-glass.png",
		    "thumb": "thumb-small-wine-glass.jpg",
		    "imgHeight": 314,
		    "fillableheight": 134,
		    "toppadding": 0,
		    "capacity": 300,
		    "drinks": drinks[0].WineSmall,
		    "info": infoArray[0].Wine
		},
		{
		    "title": "Fortified Wine Glass",
		    "img": "mask-fortified-wine-glass.png",
		    "thumb": "thumb-fortified-wine-glass.jpg",
		    "imgHeight": 267,
		    "fillableheight": 179,
		    "toppadding": 0,
		    "capacity": 100,
		    "drinks": drinks[0].FortifiedWineGlass,
		    "info": infoArray[0].Wine
		},
		{
		    "title": "Sparkling Wine Glass",
		    "img": "mask-sparkling-wine-glass.png",
		    "thumb": "thumb-sparkling-wine-glass.jpg",
		    "imgHeight": 370,
		    "fillableheight": 179,
		    "toppadding": 2,
		    "capacity": 180,
		    "drinks": drinks[0].SparklingWineGlass,
		    "info": infoArray[0].Wine
		},
		{
		    "title": "Wine Bottle",
		    "img": "mask-wine-bottle.png",
		    "thumb": "thumb-wine-bottle.jpg",
		    "imgHeight": 377,
		    "fillableheight": 260,
		    "toppadding": 0,
		    "capacity": 750,
		    "drinks": drinks[0].WineBottle,
		    "info": infoArray[0].Wine
		},
		{
		    "title": "Middy Glass",
		    "img": "mask-beer-middy-glass.png",
		    "thumb": "thumb-beer-middy-glass.jpg",
		    "imgHeight": 299,
		    "fillableheight": 263,
		    "toppadding": 6,
		    "capacity": 285,
		    "drinks": drinks[0].beerMiddy,
		    "info": infoArray[0].Beer
		},
		{
		    "title": "Schooner Glass",
		    "img": "mask-beer-schooner-glass.png",
		    "thumb": "thumb-beer-schooner-glass.jpg",
		    "imgHeight": 341,
		    "fillableheight": 300,
		    "toppadding": 6,
		    "capacity": 425,
		    "drinks": drinks[0].beerSchooner,
		    "info": infoArray[0].Beer
		},
		{
		    "title": "Pint Glass",
		    "img": "mask-beer-pint-glass.png",
		    "thumb": "thumb-beer-pint-glass.jpg",
		    "imgHeight": 380,
		    "fillableheight": 332,
		    "toppadding": 6,
		    "capacity": 570,
		    "drinks": drinks[0].beerPint,
		    "info": infoArray[0].Beer
		},
		{
		    "title": "Shot Glass",
		    "img": "mask-shot-glass.png",
		    "thumb": "thumb-shot-glass.jpg",
		    "imgHeight": 204,
		    "fillableheight": 132,
		    "toppadding": 4,
		    "capacity": 35,
		    "drinks": drinks[0].Spirits,
		    "info": infoArray[0].Spirits
		},
		{
		    "title": "Whisky glass",
		    "img": "mask-whiskey-glass.png",
		    "thumb": "thumb-whiskey-glass.jpg",
		    "imgHeight": 251,
		    "fillableheight": 155,
		    "toppadding": 2,
		    "capacity": 200,
		    "drinks": drinks[0].Whisky,
		    "info": infoArray[0].Spirits
		}
    ];

    //console.log(dataArray);
    //console.log(drinks[0].whiteWine);

    var drinkContainerImgDirectory = './DesktopModules/ATAStandardDrinkTool/images/standard-drinks/';

    for (var i = dataArray.length - 1; i >= 0; i--) {

        var row = $('.drink-container-template li').clone();

        $(row).removeClass('drink-container-template');

        var glassCapacityEach = (glassCapacity < 1000) ? dataArray[i].capacity + 'ml' : (Math.round((dataArray[i].capacity / 1000) * 100) / 100) + 'L';

        $(row).find('.container-image').attr('src', drinkContainerImgDirectory + dataArray[i].thumb);
        $(row).find('.container-title').text(dataArray[i].title);
        $(row).find('.container-capacity').text(glassCapacityEach);
        $(row).find('.radioDrinkContainer').attr('value', i);

        $('.drink-containers').prepend(row);

    };

    // when you click on the next button on the first page
    $('.drink-containers-wrapper').on("click", ".active", function () {
        // GTM tracking for container selection.
        // Note: Must be fired before step change event, as container selection is part of the current step (1).
        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'select-container',
            'eventLabel': dataArray[drinkGlassIndex].title,
            'eventValue': dataArray[drinkGlassIndex].capacity
        });

        // if there is more then 1 liquid assigned to the selected container then take them to step 2. Else, go straight to step 3
        if (dataArray[drinkGlassIndex].drinks.length > 1) {
            gtmDataLayer.push({
                'tool': 'standard-drink',
                'event': 'step-change',
                'eventLabel': 'advance',
                'eventValue': 2
            });

            step2();
        } else {
            // check the first selection seeing as theres only 1
            $(".drink-types li:eq(0)").trigger('click');

            gtmDataLayer.push({
                'tool': 'standard-drink',
                'event': 'step-change',
                'eventLabel': 'advance',
                'eventValue': 3
            });

            step3();
        }

    });

    // when you click the back button on step 3 page
    $('.drink-wrapper').on("click", ".back", function () {
        // if there is more then 1 liquid assigned to the selected container then take them to step 2
        if (dataArray[drinkGlassIndex].drinks.length > 1) {
            // GTM tracking.
            gtmDataLayer.push({
                'tool': 'standard-drink',
                'event': 'step-change',
                'eventLabel': 'retreat',
                'eventValue': 2
            });

            step2();
        } else {
            // if it only has one drink type go straight to start page
            gtmDataLayer.push({
                'tool': 'standard-drink',
                'event': 'step-change',
                'eventLabel': 'retreat',
                'eventValue': 1
            });

            step1();
        }
    });

    // when you click on the next button on the beverage type page
    $('.drink-type-buttons').on("click", ".active", function () {
        // GTM tracking.
        // Note: 'select-type' event must be fired before step change event, as type selection is part of the current step (2).
        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'select-type',
            'eventLabel': drinkName,
            'eventValue': standardDrinkMl
        });

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': 'advance',
            'eventValue': 3
        });

        step3();
    });

    // when you choose your drink container
    $('.drink-containers li').on("click", function () {

        //added "selected" class
        $('.drink-containers li').removeClass('selected');
        // add class on clicked one
        $(this).addClass('selected');
        // add active class to next button
        $('.drink-container-buttons .drink-container-selected').addClass('active');

        //check hidden radio button
        $(".radioDrinkContainer", this).prop('checked', true);

        //set the glass capacity variable
        drinkGlassIndex = $(".radioDrinkContainer", this).val();
        glassCapacity = dataArray[drinkGlassIndex].capacity;
        fillableHeight = dataArray[drinkGlassIndex].fillableheight * ratio;
        topPadding = dataArray[drinkGlassIndex].toppadding * ratio;
        imageHeight = dataArray[drinkGlassIndex].imgHeight * ratio;
        glassTitle = dataArray[drinkGlassIndex].title;

        var glassCapacityText = (glassCapacity < 1000) ? glassCapacity + 'ml' : (Math.round((glassCapacity / 1000) * 100) / 100) + 'L';

        $('.glassName').text(glassTitle);
        $('.glasscapacity').text(glassCapacityText);

        //console.log('glassCapacity: ' + glassCapacity);
        //console.log('fillableHeight: ' + fillableHeight);
        //console.log('topPadding: ' + topPadding);


        // replace step two thumb image
        $('.selected-container .container-image').attr('src', drinkContainerImgDirectory + dataArray[drinkGlassIndex].thumb + "");

        // step two title and capacity fields
        $('.selected-container .container-title').text(glassTitle);
        $('.selected-container .container-capacity').text(glassCapacityText);

        //replace the glass image
        $('.maskimage').attr('src', drinkContainerImgDirectory + dataArray[drinkGlassIndex].img + "");
        //$('.drink-mask').css('background-size', "contain");

        // loop through the drinks under that container and set up the HTML
        $('.drink-types').empty();

        for (var i = dataArray[drinkGlassIndex].drinks.length - 1; i >= 0; i--) {

            // populate the drink types
            var rowTypes = $('.drink-types-template li').clone();

            $(rowTypes).removeClass('drink-types-template');

            // populate data
            $(rowTypes).find('.drink-title').text(dataArray[drinkGlassIndex].drinks[i].typename);
            $(rowTypes).find('.drink-volume').text(dataArray[drinkGlassIndex].drinks[i].volume + '% Alcohol Volume');
            $(rowTypes).find('.drinkTypeContainer').attr('value', i);

            $('.drink-types').prepend(rowTypes);

        }

        //set default drink image - this is the first drink in the list
        //$('.drink-underlay').css('background', "url('" + drinkContainerImgDirectory + dataArray[drinkGlassIndex].drinks[0].img + "') repeat-y");
    });

    // when you choose your drink type
    $('.drink-types').on("click", 'li', function () {

        //added "selected" class
        $('.drink-types li').removeClass('selected');
        // add class on clicked one
        $(this).addClass('selected');
        // add active class to next button
        $('.drinkTypeSelelected').addClass('active');

        //check hidden radio button
        $(".drinkTypeContainer", this).prop('checked', true);

        drinkGlassIndex = $(".radioDrinkContainer:checked").val();
        //set the glass capacity variable
        drinkTypeIndex = $(".drinkTypeContainer", this).val();


        if (typeof dataArray[drinkGlassIndex].drinks[drinkTypeIndex].froth !== 'undefined') {
            froth = dataArray[drinkGlassIndex].drinks[drinkTypeIndex].froth * ratio;
            topPadding = +froth;
        } else {
            froth = 0;
        };

        // override the rounding method for specific liquids types
        if (typeof dataArray[drinkGlassIndex].drinks[drinkTypeIndex].roundMethod !== 'undefined') {
            var roundMethod = dataArray[drinkGlassIndex].drinks[drinkTypeIndex].roundMethod;

            if (roundMethod == 'floor') {
                calcStandardDrinks = function (serveml, alcoholvol) {
                    return Math.floor(((serveml / 1000) * alcoholvol * 0.789) * 10) / 10;
                };
            } else if (roundMethod == 'ceil') {
                calcStandardDrinks = function (serveml, alcoholvol) {
                    return Math.ceil(((serveml / 1000) * alcoholvol * 0.789) * 10) / 10;
                };
            }
        };

        // alcohol percentage
        alcoholVol = dataArray[drinkGlassIndex].drinks[drinkTypeIndex].volume;

        // number of standard drinks
        numStandardDrinks = calcStandardDrinks(glassCapacity, alcoholVol);

        //console.log('numStandardDrinks:' + numStandardDrinks);

        // how many ml in stardard
        standardDrinkMl = 5 * (Math.round((glassCapacity / numStandardDrinks) / 5));

        standardDrinkMl2 = (glassCapacity / numStandardDrinks);
        //console.log('standardDrinkMl: ' + standardDrinkMl2);

        //console.log('glassCapacity:' + glassCapacity);
        //console.log('numStandardDrinks:' + numStandardDrinks);
        //console.log('standardDrinkMl:' + standardDrinkMl);

        //console.log('alcoholVol: ' + alcoholVol);

        drinkName = dataArray[drinkGlassIndex].drinks[drinkTypeIndex].typename;
        moreInfo = dataArray[drinkGlassIndex].info[0].bodyCopy;

        $('.drinkName').text(drinkName);
        $('.alcoholVolume').text(alcoholVol);

        //set default drink image - this is the first drink in the list
        $('.drink-overflow').css('background-image', "url('" + drinkContainerImgDirectory + dataArray[drinkGlassIndex].drinks[drinkTypeIndex].img + "')");
    });

    var xcount = 0;
    function start() {
        add = setInterval(function () {

            $("#drag-wrapper").animate(
            {
                "top": (fillableHeight - 20)
            },
            {
                duration: 200,
                step: function (top) {
                    // the top is positions so that it creates the illusion of the glass filling up and down.
                    //$( ".drink-underlay" ).css("top", ((top) + "px"));
                    // the reason why the height shrinks is so that the layer isnt visible outside the mask once it goes to the bottom
                    // ie. at the bottom the height will be 0px. At the top it will be full fillable height
                    //$( ".drink-underlay" ).css("height", ((fillableHeight - top) + "px"));
                    $('.drink-overflow').css("background-position", "center " + (top + "px"));
                }
            }),

            $("#drag-wrapper").animate(
            {
                "top": (fillableHeight)
            },
            {
                duration: 200,
                step: function (top) {
                    // the top is positions so that it creates the illusion of the glass filling up and down.
                    //$( ".drink-underlay" ).css("top", ((top) + "px"));
                    // the reason why the height shrinks is so that the layer isnt visible outside the mask once it goes to the bottom
                    // ie. at the bottom the height will be 0px. At the top it will be full fillable height
                    //$( ".drink-underlay" ).css("height", ((fillableHeight - top) + "px"));
                    $('.drink-overflow').css("background-position", "center " + (top + topPadding) + "px");
                }
            });

        }, 5000);
    }

    // start the subtle movement of the liquid every x seconds if the person hasnt dragged
    start();

    // sets/resets all the values
    function initTool() {
        $(".radioDrinkContainer").prop('checked', false);
        $(".drinkTypeContainer").prop('checked', false);
    }


    // sets/resets all the values
    function resetGlass() {
        $('.guessBtn').hide();
        $('.drink-inner-wrapper').css("height", imageHeight);
        $('.maskimage').css("height", imageHeight);
        $('.drink-overflow').css("height", imageHeight);
        $('.underlay-wrapper').css("margin-top", (topPadding + "px"));
        $('.underlay-wrapper').css("height", (fillableHeight * 2));
        $("#drag-wrapper").css("height", (fillableHeight + "px"));
        $("#drag-wrapper").css("top", 0);
        $("#drag-wrapper").animate(
									{
									    "top": fillableHeight
									},
									{
									    duration: 600,
									    step: function (top) {
									        // the top is positions so that it creates the illusion of the glass filling up and down.
									        //$( ".drink-underlay" ).css("top", ((top) + "px"));
									        // the reason why the height shrinks is so that the layer isnt visible outside the mask once it goes to the bottom
									        // ie. at the bottom the height will be 0px. At the top it will be full fillable height
									        //$( ".drink-underlay" ).css("height", ((fillableHeight - top) + "px"));
									        $('.drink-overflow').css("background-position", "center " + (top + topPadding) + "px");
									        //  y axis from top of container
									        pourLevel = (top - fillableHeight) * -1;
									        // pertange of the height ie. 100% = 1.0
									        pourLevelPercent = (pourLevel / fillableHeight);
									        // how many mils are filled
									        filledMl = (glassCapacity * pourLevelPercent);
									        // round to nearest 5ml
									        filledMlRounded = 5 * (Math.round(filledMl / 5));

									        $('.filled span').text(filledMlRounded);
									    }
									});
        $(".standardGuide").hide();
        $(".standardGuide").css("top", fillableHeight);
        $('.filledStandardDrink').hide();
        $('.dragbox').removeClass('checked');
        dynamicStandardDrink = false;

    }


    // step 1 events, usually used by buttons
    function step1() {
        $('.sub-head-italic').text('Choose your container:');
        // remove active state on step 2 next button
        $('.drink-containers li').removeClass('selected');
        $('.drink-container-selected').removeClass('active');

        $('.drink-containers-wrapper').fadeIn();
        $('.drink-types-wrapper').hide();
        $('.drink-wrapper').hide();
        $('.sdt-disclaimer').hide();
        $('.more-information').hide();

        if (isMobile) {
            $('html, body').animate({
                scrollTop: $(".drink-containers-wrapper").offset().top
            }, 500);
        }

        $('.flexslider').flexslider(1); $('.flexslider').flexslider(0);
    }

    // step 2 events, usually used by buttons
    function step2() {
        $('.sub-head-italic').text('Choose your beverage:');
        // remove active state on step 2 next button
        $('.drink-types li').removeClass('selected');
        $('.drinkTypeSelelected').removeClass('active');

        $('.drink-containers-wrapper').hide();
        $('.drink-types-wrapper').fadeIn();
        $('.drink-wrapper').hide();
        $('.sdt-disclaimer').hide();
        $('.more-information').hide();
        if (isMobile) {
            $('html, body').animate({
                scrollTop: $(".drink-types-wrapper").offset().top
            }, 500);
        }
    }

    // step 2 events, usually used by buttons
    function step3() {
        resetGlass();
        $('.sub-head-italic').text('Compare your pour to a standard drink and evaluate how many standard drinks it contains.');
        $('.drink-containers-wrapper').hide();
        $('.drink-types-wrapper').hide();
        $('.drink-wrapper').fadeIn();
        $('.sdt-disclaimer').fadeIn();

        if (isMobile) {
            $('html, body').animate({
                scrollTop: $(".drink-wrapper").offset().top
            }, 500);
        }
    }


    $("#drag-wrapper").draggable(
      {
          handle: '.dragbox',
          axis: "y",
          containment: '.underlay-wrapper',
          //grid: [ 10, 10 ],
          drag: function (event, ui) {

              // stops the drink from going up and down every 3 seconds when the drink loads and the person hasnt dragged it
              clearInterval(add);

              //  y axis from top of container
              pourLevel = (ui.position.top - fillableHeight) * -1;
              // pertange of the height ie. 100% = 1.0
              pourLevelPercent = (pourLevel / fillableHeight);
              // how many mils are filled
              filledMl = (glassCapacity * pourLevelPercent);
              // round to nearest 5ml
              filledMlRounded = 5 * (Math.round(filledMl / 5));

              $('.filled span').text(filledMlRounded);

              // the top is positions so that it creates the illusion of the glass filling up and down.
              //$( ".drink-underlay" ).css("top", ((ui.position.top) + "px"));
              // the reason why the height shrinks is so that the layer isnt visible outside the mask once it goes to the bottom
              // ie. at the bottom the height will be 0px. At the top it will be full fillable height
              //$( ".drink-underlay" ).css("height", ((fillableHeight - ui.position.top) + "px"));

              $('.drink-overflow').css("background-position", "center " + ((ui.position.top + topPadding - froth) + "px"));

              if (dynamicStandardDrink == true) {
                  // how many standard drinks is the users guess
                  filledStandardDrink = calcStandardDrinks(filledMlRounded, alcoholVol);
                  $('.filledStandardDrink span').text(filledStandardDrink.toFixed(1));
              } else {
                  $('.guessBtn').fadeIn();
              }


              // console.log(dynamicStandardDrink);
              // console.log('top: ' + ui.position.top);

              //console.log('level: ' + pourLevel);
              //console.log('level: ' + pourLevelPercent);
              if (ui.position.top < 200) {
                  //alert('Return back');
                  //$("#drag-wrapper").animate({"top": "0px"}, 1);
                  //$("#drag-wrapper").css("display", "none");
              } else {
                  //$("#drag-wrapper").css("display", "block");
              }
          }
      }
    );

    $('.initTool').on("click", function () {
        initTool();
    });

    $('.step1').on("click", function () {
        var eventLabel = ($(this).hasClass('js-back') || $(this).hasClass('js-restart')) ? 'retreat' : 'advance';

        if ($(this).hasClass('js-restart')) {
            eventLabel = 'restart';
        }

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': eventLabel,
            'eventValue': 1
        });

        step1();
    });

    // .drink-wrapper .step2 has a special click handler and is therefore ignored.
    $('.step2:not(".drink-wrapper .back")').on("click", function () {
        var eventLabel = ($(this).hasClass('js-back')) ? 'retreat' : 'advance';

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': eventLabel,
            'eventValue': 2
        });
        console.log('.step2 click');
        step2();
    });

    $('.step3').on("click", function () {
        var eventLabel = ($(this).hasClass('js-back')) ? 'retreat' : 'advance';

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': eventLabel,
            'eventValue': 3
        });

        step3();
    });

    $('.guessBtn').on("click", function () {
        $('.dragbox').addClass('checked');
        $('.guessBtn').fadeOut();

        // how many standard drinks is the users guess
        $('.filledStandardDrink').show();
        filledStandardDrink = calcStandardDrinks(filledMlRounded, alcoholVol);
        $('.filledStandardDrink span').text(filledStandardDrink.toFixed(1));
        $('.more-information').fadeIn();
        $('.more-info-copy').html(moreInfo);

        // GTM tracking.
        // Note: It's important that the step change occurs after the pour selection is recorded, as the
        //       the pour selection is really part of the current step (3).
        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'select-pour',
            'eventLabel': filledStandardDrink.toFixed(1),
            'eventValue': filledMlRounded,
        });

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'step-change',
            'eventLabel': 'advance',
            'eventValue': 4
        });

        // figure out the level for the standard drink

        var pixelPerMl = (fillableHeight / glassCapacity);

        var standardDrinkLevel = fillableHeight - (pixelPerMl * standardDrinkMl);

        // console.log('fillableHeight: ' + fillableHeight);
        // console.log('topPadding: ' + topPadding);
        // console.log('pixelPerMl: ' + pixelPerMl);
        //console.log('standardDrinkMl--: ' + standardDrinkMl);
        // console.log('standardDrinkLevel: ' + standardDrinkLevel);

        $(".standardGuide").css("top", ((fillableHeight) + "px"));

        // if a full glass is LESS then 1 standard drink. Just show how much the full glass is. (this is done because otheriwse the line would go above the glass)
        if (standardDrinkLevel < 0) {

            var fullGlassStandardDrink = calcStandardDrinks(glassCapacity, alcoholVol);

            $('.standardDrinkMl span').text(glassCapacity);
            $('.sd-num').text(fullGlassStandardDrink.toFixed(1));
            standardDrinkLevel = 0;
        } else {
            $('.sd-num').text('1.0');
            $('.standardDrinkMl span').text(standardDrinkMl);
        }

        $(".standardGuide").fadeIn().animate({ "top": standardDrinkLevel }, 600);

        dynamicStandardDrink = true;

        // GTM tracking for result.
        // The label used will indicate whether the user's guess is equal, under or over the standard drink amount (in ml) for their selection.
        if (standardDrinkMl == filledMlRounded) {
            var eventLabel = 'equal';
        }
        else {
            var eventLabel = (standardDrinkMl > filledMlRounded) ? 'under' : 'over';
        }

        gtmDataLayer.push({
            'tool': 'standard-drink',
            'event': 'display-result',
            'eventLabel': eventLabel,
        });
    });


    // to do - remove
    // only for testing purposes
    // $('.drink-containers li:eq(9)').click();
    // $('.drink-containers-wrapper .active').click();
    // $('.drink-types li:eq(0)').click();
    // $('.drink-type-buttons .active').click();
    //$('.guessBtn').click();


});

