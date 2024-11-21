<?php 
$options = getOptions_rb();
$current_url = $_SERVER['REQUEST_URI'];
//echo $current_url;
?>

<meta charset="utf-8">

<?php if($current_url == '/ringbuilder/settings/') :?>
    <meta name="description" content="<?php echo $options['ring_meta_description']; ?>" />
    <meta name="keywords" content="<?php echo $options['ring_meta_keywords']; ?>">
    <title> <?php echo $options['ring_meta_title']; ?> </title>
<?php endif; ?>

<?php if($current_url == '/ringbuilder/diamondlink/') :?>
    <meta name="description" content="<?php echo $options['diamond_meta_description']; ?>" />
    <meta name="keywords" content="<?php echo $options['diamond_meta_keyword']; ?>">
    <title> <?php echo $options['diamond_meta_title']; ?> </title>
<?php endif; ?>

<style type="text/css">

    ::selection {
        background-color: #E13300;
        color: white;
    }

    ::-moz-selection {
        background-color: #E13300;
        color: white;
    }

    /* body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	} */

    a {
        color: #003399;
        background-color: transparent;
        font-weight: normal;
    }

    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 19px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }

    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 12px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }

    #body {
        margin: 0 15px 0 15px;
    }

    p.footer {
        text-align: right;
        font-size: 11px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }

    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
</style>
<script type="text/javascript">
    jQuery('body').addClass('gemfind-tool-ringbuilder');
</script>