<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Bootstrap 4 Admin &amp; Dashboard Template">
	<meta name="author" content="Bootlab">

	<title><?=$title?></title>

	<!--<link rel="preconnect" href="//fonts.gstatic.com/" crossorigin>-->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab:300&display=swap" rel="stylesheet">

	<!-- PICK ONE OF THE STYLES BELOW -->
	<!--<link href="<?=base_url()?>public/app_stack/css/classic.css" rel="stylesheet">-->
	<!-- <link href="<?=base_url()?>public/app_stack/css/corporate.css" rel="stylesheet"> -->
	 <link href="<?=base_url()?>public/app_stack/css/modern.css" rel="stylesheet"> 
	<link href="<?=base_url()?>public/app_stack/css/custom.css" rel="stylesheet">
	<!-- BEGIN SETTINGS -->
	<!-- You can remove this after picking a style -->
	<style>
		body {
			opacity: 0;
			font-family: 'Roboto Slab', serif;
	        font-size: 12px;
		}
	</style>
	<!-- 15-05-2020 ranjeet css -->
	<style>
    #map-plug {display:none;}
.google-reviews,.google_reviews {
display:flex;
flex-wrap:wrap;
/* //display: grid; */
/* //grid-template-columns: repeat( auto-fit, minmax(320px, 1fr)); */
width: 100px;
height: 100px;
background-color: white;
}
.review-item {
border:solid 1px rgba(190,190,190,.35);
margin:0 auto;
padding:1em;
flex: 1 1 20%;
background-color: white;
}
@media ( max-width:1200px) {
  .review-item { flex: 1 1 40%; }
}
@media ( max-width:450px) {
  .review-item { flex: 1 1 90%; }
}
.review-meta, .review-stars {text-align:center; font-size:115%;}
.review-author { text-transform: capitalize; font-weight:bold; }
.review-date {opacity:.6; display:block;}
.review-text {  line-height:1.55; text-align:left; max-width:32em; margin:auto;}
.review-stars ul {
display: inline-block;
list-style: none;
margin:0; padding:0;
}
.review-stars ul li {
float: left;
margin-right: 1px;
line-height:1;
}
.review-stars ul li i {
  color: #E4B248;
  font-size: 1.4em;
  font-style:normal;
}
.review-stars ul li i.inactive { color: #C6C6C6;}
.star:after { content: "\2605"; }
</style>
	<!-- END SETTINGS -->
</head>
<script>
	 window.addEventListener("load", function(){
	 var load_screen = document.getElementById("load_screen");
	    $(load_screen).hide();
	 });
</script> 
<body>
    <div id="load_screen">
    	<div class="spinner-border text-primary mr-2 custom-spinner" role="status">
			<span class="sr-only">Loading...</span>
		</div>
    </div>