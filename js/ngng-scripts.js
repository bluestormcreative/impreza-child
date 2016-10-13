/**
 * Banner Margins
 *
 * @author Shannon MacMillan
 */
window.BannerMargin = {};
( function( window, $, app ) {

    // Constructor
    app.init = function() {
        app.cache();

        if ( app.meetsRequirements() ) {
            app.bindEvents();
        }
    };

    // Cache all the things
    app.cache = function() {
        app.$c = {
            window: $(window),
            banner: $( '#banner' ),
            bannerContent: $( '#banner-content' ),
            optin: $( '#top-optin' ),
            header: $( '.l-header.pos_fixed'),
        };
    };

    // Combine all events
    app.bindEvents = function() {
        app.$c.window.on( 'load', app.doBannerMargin );
    };

    // Do we meet the requirements?
    app.meetsRequirements = function() {
        return app.$c.header.length;
    };

    // Some function
    app.doBannerMargin = function() {
        // Get the height of the header on page load.
        var headerHeight = app.$c.header.outerHeight();

    // If we have a banner area...
    if ( app.$c.banner ) {
        // Set the top margin of our banner to the height of the fixed header.
        app.$c.banner.css( 'margin-top', headerHeight + 'px');

    }

    if ( app.$c.bannerContent) {
        // Set the top margin of our banner to the height of the fixed header.
        app.$c.bannerContent.css( 'margin-top', headerHeight + 'px');
    }

    if ( !app.$c.banner && !app.$c.bannerContent ) {
        // Set the top margin of our banner to the height of the fixed header.
        app.$c.optin.css( 'margin-top', headerHeight + 'px');
    }

        // When the window is resized...
        app.$c.window.resize( function() {

            // Get the height of the header again...
            headerHeight = app.$c.header.outerHeight();
            // And set it to the top margin of the banner again.
            app.$c.banner.css( 'margin-top', headerHeight + 'px');

        });
    };

    // Engage
    $( app.init );

})( window, jQuery, window.BannerMargin );
