<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="shortcut icon" href="./images/short_url.png" type="image/x-icon"/>
    <meta charset="UTF-8"/>
    <title>{!TITLE!}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=0.7, user-scalable=no' name='viewport'>
    <meta name="description" content="{!DESCRIPTION!}">
    <meta name="keywords" content="{!KEYWORDS!}">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="./css/style.css">

    <!-- Поддержка html5 и медиа запросов в IE -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body style="background: #108a93;">

<script type="text/javascript">document.body.ondragstart = function () {
        return false;
    };</script>

<div class="before-start">
    <img class="l_logo" src="./images/short_url.png" onClick="window.location.reload();" style="cursor: pointer;">
    <span><?= $config->Default_Site_Title; ?></span>
</div>

<div class="container">
    <form method="POST" action="" class="check_make_s_u" id="sendFormUrl">
        <div class="row" style="">

            <div class="col-xs-12 site_name text-center">
                <span style="text-shadow: 3px 2px 3px black;">Make your links shorter ...</span>
            </div>

            <div class="col-xs-12 col-md-12">
                <div class="input-group">
                    <input type="text" class="form-control user_long_url" name="user_long_url">
                    <input type="hidden" name="check_id" value="<?= session_id(); ?>">
                    <span class="input-group-btn">
					<button class="btn btn-primary make_s_u" type="submit"><strong>Make Short URL!</strong></button>
				</span>
                </div>

                <div class="alert alert-danger alert-dismissable error_long_url text-center"
                     style="padding: 5px; margin: 10px 0px 10px 0px;">
                    <strong style="font-size: 15px; font-weight: bold;">Please Enter Your Original URL</strong>.
                </div>

                <div class="alert alert-danger alert-dismissable error_msg text-center"
                     style="padding: 5px; margin: 10px 0px 10px 0px;">
                    <strong style="font-size: 15px; font-weight: bold;">Your URL returned status
                        <span style="color: black;" class="error_msg_code"></span>. Check this URL manually
                    </strong>.
                </div>

                <hr>
            </div>

            <div class="col-xs-10 col-md-12">
                <label for="disabledTextInput">
                    User choice (optional), you can use letters, underline and numbers, lenght (
                    <?php echo $config->Default_Min_URL_Length . ' - ' . $config->Default_Max_URL_Length; ?>
                    ) symbols:
                </label>
            </div>

        </div>

        <div class="row">

            <div class="col-xs-12 col-md-7">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/</span>
                    <input type="text" class="form-control user_choice_data" onChange="DPCM(this);"
                           onKeyUp="DPCM(this);" name="user_choice_data">
                    <span class="input-group-btn">
					<button class="btn btn-primary check_u_ch_d"
                            type="button"><strong>Check You Choice!</strong></button>
				</span>
                </div>

                <div class="alert alert-danger alert-dismissable error_msg_u_ch_d text-center"
                     style="padding: 5px; margin: 10px 0px 10px 0px;">
                    <strong style="font-size: 15px; font-weight: bold;">This URL busy.</strong>
                </div>

                <div class="alert alert-success alert-dismissable ok_msg_u_ch_d text-center"
                     style="padding: 5px; margin: 10px 0px 10px 0px;">
                    <strong style="font-size: 15px; font-weight: bold;">This URL available.</strong>
                </div>

            </div>

            <div class="col-xs-12 col-md-12" style="margin-top: 30px;">
                <div class="alert alert-success alert-dismissable ok_msg text-center"
                     style="padding: 5px; margin: 10px 0px 10px 0px;">
                    <strong style="font-size: 15px; font-weight: bold;">Your Short URL created successfully</strong>.
                </div>
            </div>

            <div class="col-xs-12 col-md-7 result_make_url_div">
                <div class="input-group">
                    <span class="input-group-addon copyButton" style="cursor: pointer;" id="copyButton"><span
                                class="glyphicon glyphicon-duplicate"></span> To Clipboard</span>
                    <input type="text" class="form-control result_make_url" id="copyTarget" value="">
                </div>
            </div>

        </div>

    </form>
</div>
<script src="./js/functions.js"></script>
<script>
    function DPCM(input) {
        var value = input.value;
        var re = /[^0-9a-z_]/gi;
        var str_len = value.length;

        if (str_len > <?=$config->Default_Max_URL_Length; ?> ) {
            input.value = value.slice(0, <?=$config->Default_Max_URL_Length; ?> );
        }

        if (re.test(value)) {
            value = value.replace(re, '');
            input.value = value;
        }
    }

    $(document).ready(function () {

        $(".copyButton").click(function () {
            $(".result_make_url").animate({
                    backgroundColor: "green"
                }, 100, function () {
                    $(".result_make_url").animate({backgroundColor: ""}, 500);
                }
            );
        });

        $(".check_make_s_u").submit(function () {

            var _u_l_u = $.trim($('.user_long_url').val());

            if (_u_l_u == '') {
                $('.error_long_url').show();

                setTimeout(function () {
                        $('.error_long_url').fadeOut();
                    }, 3000
                );

                return false;
            }

            var datastring = $("#sendFormUrl").serialize();
            $.ajax({
                type: "POST",
                url: "./check_long_url.php",
                data: datastring,
                dataType: "json",
                success: function (data) {

                    if (data.error === true) {

                        if (String(data.code) === '0000') {

                            $('.error_msg_u_ch_d').show();
                            $('.error_msg').show();
                            $('.error_msg_code').html('');
                        } else {

                            $('.error_msg').show();
                            $('.error_msg_code').html(data.code);
                        }
                    } else {
                        $('.error_msg').hide();
                        $('.ok_msg').show();

                        $('.result_make_url').val('<?php echo 'http://' . $_SERVER['HTTP_HOST']; ?>/' + data.short_url);
                        $('.result_make_url_div').show();

                        event.preventDefault();
                    }
                },
                error: function () {

                    console.log('error');
                }
            });

            event.preventDefault();
        });

        $('.check_u_ch_d').click(function () {

            $('.error_msg_u_ch_d, .ok_msg_u_ch_d').hide();

            var _c_u_ch_d = $.trim($('.user_choice_data').val());

            if (_c_u_ch_d != '') {

                $.post("./check_choice.php", {
                    url_check: $('.user_choice_data').val(),
                    check_id: '<?=session_id(); ?>'
                }, function (data) {

                    var obj = jQuery.parseJSON(data);

                    if (obj.error === true) {

                        $('.error_msg_u_ch_d').show();

                        setTimeout(function () {
                                $('.error_msg_u_ch_d').fadeOut();
                            }, 2000
                        );

                        return false;

                    } else {
                        $('.ok_msg_u_ch_d').show();
                    }
                });

            } else {
                return false;
            }
        });

    })
</script>