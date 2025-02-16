<!-- header styles -->

<?php
   $localFonts = apply_filters('get_local_fonts', '');
?>
<?php if ($localFonts) : ?> 
   <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/<?php echo $localFonts; ?>" media="screen" type="text/css" />
<?php else : ?>
   <?php endif; ?>
<link id="u-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">
<style> .u-header {
  background-image: none;
}
.u-header .u-sheet-1 {
  min-height: 79px;
}
.u-header .u-image-1 {
  width: 64px;
  height: 32px;
  margin: 22px auto 0 0;
}
.u-header .u-logo-image-1 {
  width: 100%;
  height: 100%;
}
.u-header .u-menu-1 {
  margin: -32px 0 0 auto;
}
.u-header .u-nav-1 {
  font-size: 1rem;
  letter-spacing: 0px;
}
.u-header .u-nav-2 {
  font-size: 1.25rem;
}
.u-header .u-shopping-cart-1 {
  height: 24px;
  width: 24px;
  margin: -30px auto 28px 94px;
}
@media (max-width: 1199px) {
  .u-header .u-menu-1 {
    width: auto;
  }
  .u-header .u-shopping-cart-1 {
    margin-left: 94px;
  }
}</style>
