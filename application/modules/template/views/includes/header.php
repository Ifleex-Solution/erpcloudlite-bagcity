<style>
    .navbar h3 {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        margin: 0;

    }

    .instance-badge {
        position: absolute;
        left: 70px;
        top: 50%;
        transform: translateY(-50%);
        padding: 4px 10px;
        border-radius: 3px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-family: inherit;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .instance-dev {
        background-color: #d9534f;
        color: #fff;
    }

    .instance-uat {
        background-color: #f0ad4e;
        color: #fff;
    }

    .instance-beta {
        background-color: #337ab7;
        color: #fff;
    }

    .instance-prod {
        background-color: #5cb85c;
        color: #fff;
    }

    .instance-live {
        background-color: #5cb85c;
        color: #fff;
    }
</style>
<a href="<?php echo base_url('home') ?>" class="logo">
    <span class="logo-lg">
        <img src="<?php echo base_url((!empty($setting->logo) ? $setting->logo : 'assets/img/icons/mini-logo.png')) ?>"
            alt="">
    </span>
    <span class="logo-mini">
        <img src="<?php echo base_url((!empty($setting->favicon) ? $setting->favicon : 'assets/img/icons/mini-logo.png')) ?>"
            alt="">
    </span>
</a>
<div class="se-pre-con"></div>
<!-- Header Navbar -->
<?php $gui_p = $this->uri->segment(1);
if ($gui_p != 'gui_pos') {
?>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <!-- Sidebar toggle button-->
            <span class="sr-only">Toggle navigation</span>
            <span class="pe-7s-keypad"></span>
        </a>

        <!-- Instance Badge in Top Left -->
        <?php
        if (isset($company_info2[0]['instance_type']) && !empty($company_info2[0]['instance_type'])):
            $instance_class = 'instance-' . strtolower($company_info2[0]['instance_type']);
        ?>
            <span class="instance-badge <?php echo $instance_class; ?>">
                <?php echo $company_info2[0]['instance_type']; ?>
            </span>
        <?php endif; ?>

        <!-- Company Name/Header Text in the Middle -->
        <h3><?php echo !empty($company_info[0]['header_text']) ? $company_info[0]['header_text'] : $company_info[0]['company_name']; ?></h3>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages -->
                <?php if ($max_version > $current_version) { ?>
                    <li>
                        <blink><a href="<?php echo base_url('autoupdate/Autoupdate') ?>"
                                class="text-white btn-danger update-btn">
                                <?php echo $max_version . ' Version Available'; ?>
                            </a></blink>
                    </li>
                <?php } ?>

                <!-- Notifications -->
                <?php if (
                    $this->permission1->method('stock_alert', 'create')->access()

                ) { ?>

                    <li class="dropdown notifications-menu">
                        <a href="<?php echo base_url('out_of_stock') ?>">
                            <i class="pe-7s-attention" title="<?php echo display('out_of_stock') ?>"></i>
                            <span class="label label-danger"><?php echo html_escape($out_of_stocks) ?></span>
                        </a>
                     </li>
                <?php } ?>

                 <?php if (
                    $this->permission1->method('expiry_alert', 'create')->access()

                ) { ?>

                <li class="dropdown notifications-menu">
                    <a href="<?php echo base_url('expiry_alert') ?>">
                        <i class="pe-7s-date" title="Expiry Alert"></i>
                        <span class="label label-warning"><?php echo html_escape($expiry_alert_count) ?></span>
                    </a>
                </li>

                   <?php } ?>

                <!-- Settings -->
                <li class="dropdown dropdown-user">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('view_profile') ?>"><i class="pe-7s-users"></i> View Profile</a>
                        </li>
                        <li><a href="<?php echo base_url('logout') ?>"><i class="pe-7s-key" style="margin-right: 17px;"></i>
                                <?php echo display('logout') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

<?php } ?>