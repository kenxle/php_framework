<?php
/* http://developers.facebook.com/docs/opengraph/ */
/* http://ogp.me/ */

/*
 * This page takes care of the open graph data for facebook. 
 * It only prints the tags that you assign, so it can be used in all pages. 
 * This page must be included inside the <head> tag. 
 */


/* 
 * Use the URL Linter to double check your tags:
 * http://developers.facebook.com/tools/lint
 * 
 * You can also visit or cURL the linter to force update any meta changes
 * curl http://developers.facebook.com/tools/lint/?url={YOUR_URL}&format=json
 */


/* 
 * The Open Graph protocol defines four required properties:

		og:title - The title of your object as it should appear within the graph, e.g., "The Rock".
		og:type - The type of your object, e.g., "movie". See the complete list of supported types.
		og:image - An image URL which should represent your object within the graph. The image must be at least 50px by 50px and have a maximum aspect ratio of 3:1. We support PNG, JPEG and GIF formats. You may include multiple og:image tags to associate multiple images with your page.
		og:url - The canonical URL of your object that will be used as its permanent ID in the graph, e.g., http://www.imdb.com/title/tt0117500/.
		
	In addition, we've extended the basic meta data to add two required fields to connect your webpage with Facebook:
	
		og:site_name - A human-readable name for your site, e.g., "IMDb".
		fb:admins or fb:app_id - A comma-separated list of either Facebook user IDs or a Facebook Platform application ID that administers this page. It is valid to include both fb:admins and fb:app_id on your page.
	
	It's also recommended that you include the following property as well as these multi-part properties.
	
		og:description - A one to two sentence description of your page.
	
	If a user likes your URL using a Like button, a News Feed story similar to the one below will be published to Facebook. The og:title links to og:url and the og:site_name is rendered pointing to your site's domain automatically.
	
	On a user's profile, og:type defines which category your link will appear within; og:image is the image thumbnail.
 */

/* og:title - The title of your object as it should appear within the graph, e.g., "The Rock". 
 * After your page receives 50 likes, the title becomes fixed. 
 */
if($og_title){?>
	<meta property="og:title" content="<?=$og_title?>"/>
<?}

/*
 * og:type - The type of your object, e.g., "movie". 
 * After your page receives 10,000 likes, the type becomes fixed. 
 * 
 * og:type possible values
 * Use article for any URL that represents transient content - such as a news article, blog post, photo, video, etc. Do not use website for this purpose. website and blog are designed to represent an entire site, an og:type tag with types website or blog should usually only appear on the root of a domain.
 * 
 * If your object does not fit into one of the types above, you can specify your own type. This will be represented as type other on Facebook. We will monitor the most commonly used types and graduate them to fully supported og:types.
 * 
 * Activities

	activity
	sport
	
Businesses
	
	bar
	company
	cafe
	hotel
	restaurant

Groups
	
	cause
	sports_league
	sports_team

Organizations
	
	band
	government
	non_profit
	school
	university

People
	
	actor
	athlete
	author
	director
	musician
	politician
	public_figure

Places
	
	city
	country
	landmark
	state_province

Products and Entertainment
	
	album
	book
	drink
	food
	game
	product
	song
	movie
	tv_show
	
For products which have a UPC code or ISBN number, you can specify them using the og:upc and og:isbn properties. These properties help uniquely identify products.

Websites
	
	blog
	website
	article
 */
if($og_type){?>
	<meta property="og:type" content="<?=$og_type?>"/>
<?}

/* og:image - An image URL which should represent your object within the graph. The image must be at least 50px by 50px and have a maximum aspect ratio of 3:1. We support PNG, JPEG and GIF formats. You may include multiple og:image tags to associate multiple images with your page. */
if($og_image){?>
	<meta property="og:image" content="<?=$og_image?>"/>
<?}

/* og:url - The canonical URL of your object that will be used as its permanent ID in the graph, e.g., http://www.imdb.com/title/tt0117500/. */
if($og_url){?>
	<meta property="og:url" content="<?=$og_url?>"/>
<?}

/* og:site_name - A human-readable name for your site, e.g., "IMDb". */
if($og_site_name){?>
	<meta property="og:site_name" content="<?=$og_site_name?>"/>
<?}

/* 
 * fb:admins or fb:app_id - A comma-separated list of either Facebook user IDs or a Facebook Platform application ID that administers this page. It is valid to include both fb:admins and fb:app_id on your page. 
 * 
 * To associate a page with a person and not a business page, use fb:admins. 
 * Each person listed as an admin must click the like button to consent to being an admin
 */
if($fb_admins){?>
	<meta property="fb:admins" content="<?=$fb_admins?>"/>
<?}
if($fb_app_id){?>
	<meta property="fb:app_id" content="<?=$fb_app_id?>"/>
<?}

/* og:description - A one to two sentence description of your page. */
if($og_description){?>
	<meta property="og:description" content="<?=$og_description?>"/>
<?}


/*
 * Location and Contact Info
 *  <meta property="og:latitude" content="37.416343"/>
    <meta property="og:longitude" content="-122.153013"/>
    <meta property="og:street-address" content="1601 S California Ave"/>
    <meta property="og:locality" content="Palo Alto"/>
    <meta property="og:region" content="CA"/>
    <meta property="og:postal-code" content="94304"/>
    <meta property="og:country-name" content="USA"/>
    
    <meta property="og:email" content="me@example.com"/>
    <meta property="og:phone_number" content="650-123-4567"/>
    <meta property="og:fax_number" content="+1-415-123-4567"/>
 */
if($og_lattitue){?>
	<meta property="og:lattitude" content="<?=$og_lattitue?>"/>
<?}

if($og_longitude){?>
	<meta property="og:longitude" content="<?=$og_longitude?>"/>
<?}

if($og_street_address){?>
	<meta property="og:street-address" content="<?=$og_street_address?>"/>
<?}

if($og_locality){?>
	<meta property="og:locality" content="<?=$og_locality?>"/>
<?}

if($og_region){?>
	<meta property="og:region" content="<?=$og_region?>"/>
<?}

if($og_postal_code){?>
	<meta property="og:postal-code" content="<?=$og_postal_code?>"/>
<?}

if($og_country_name){?>
	<meta property="og:country-name" content="<?=$og_country_name?>"/>
<?}

if($og_email){?>
	<meta property="og:email" content="<?=$og_email?>"/>
<?}

if($og_phone_number){?>
	<meta property="og:phone_number" content="<?=$og_phone_number?>"/>
<?}

if($og_fax_number){?>
	<meta property="og:fax_number" content="<?=$og_fax_number?>"/>
<?}
 

/*
 * If you want to attach a video to your Open Graph page you can simply specify a video url:

	og:video - e.g., "http://example.com/awesome.flv"
	and optionally, you can add additional metadata
	
	og:video:height - e.g. "640"
	og:video:width - e.g. "385"
	og:video:type - e.g. "application/x-shockwave-flash"
	Facebook supports embedding video in SWF format only. You must include a valid og:image for your video to be displayed in the news feed.
	
	For example:
	
	<html xmlns:og="http://ogp.me/ns#"> 
	    <head>
	        ...
	        [REQUIRED TAGS]
	        <meta property="og:video" content="http://example.com/awesome.flv" />
	        <meta property="og:video:height" content="640" />
	        <meta property="og:video:width" content="385" />
	        <meta property="og:video:type" content="application/x-shockwave-flash" />
	        ...
	    </head>
	In a similar fashion to og:video you can add an audio file to your markup:
	
	og:audio - e.g., "http://example.com/amazing.mp3"
	and optionally
	
	og:audio:title - e.g. "Amazing Soft Rock Ballad"
	og:audio:artist - e.g. "Amazing Band"
	og:audio:album - e.g. "Amazing Album"
	og:audio:type - e.g. "application/mp3"
	For example:
	
	<html xmlns:og="http://ogp.me/ns#">
	    <head>
	        ...
	        [REQUIRED TAGS]
	        <meta property="og:audio" content="http://example.com/amazing.mp3" />
	        <meta property="og:audio:title" content="Amazing Song" />
	        <meta property="og:audio:artist" content="Amazing Band" />
	        <meta property="og:audio:album" content="Amazing Album" />
	        <meta property="og:audio:type" content="application/mp3" />
	        ...
	  </head>

 */
if($og_video){?>
	<meta property="og:video" content="<?=$og_video?>"/>
<?}
if($og_video_height){?>
	<meta property="og:video:height" content="<?=$og_video_height?>"/>
<?}
if($og_video_width){?>
	<meta property="og:video:width" content="<?=$og_video_width?>"/>
<?}
if($og_video_type){?>
	<meta property="og:video:type" content="<?=$og_video_type?>"/>
<?}
if($og_audio){?>
	<meta property="og:audio" content="<?=$og_audio?>"/>
<?}
if($og_audio_title){?>
	<meta property="og:audio:title" content="<?=$og_audio_title?>"/>
<?}
if($og_audio_artist){?>
	<meta property="og:audio:artist" content="<?=$og_audio_artist?>"/>
<?}
if($og_audio_album){?>
	<meta property="og:audio:album" content="<?=$og_audio_album?>"/>
<?}
if($og_audio_type){?>
	<meta property="og:audio:type" content="<?=$og_audio_type?>"/>
<?}

/* 
 * For products which have a UPC code or ISBN number, you can specify them 
 * using the og:upc and og:isbn properties. These properties help uniquely identify products.
 */
if($og_upc){?>
	<meta property="og:upc" content="<?=$og_upc?>"/>
<?}
if($og_isbn){?>
	<meta property="og:isbn" content="<?=$og_isbn?>"/>
<?}


 