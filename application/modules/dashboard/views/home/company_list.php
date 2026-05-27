<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <span id="title"><?php echo display('company_list') ?></span>
                    <span class="padding-lefttitle">
                        <table>
                            <tr>
                                <td style="padding-left: 20px;">
                                    <?php if ($this->permission1->method('add_company', 'create')->access()) { ?>
                                        <a href="<?php echo base_url('add_company') ?>" class="btn btn-primary m-b-5 m-r-2"><i class="ti-plus"> </i> <?php echo display('add_company') ?> </a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </span>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <style>
                        .company-name-nowrap {
                            white-space: nowrap;
                        }

                        .company-text-cell {
                            white-space: normal;
                            overflow: visible;
                            overflow-wrap: break-word;
                            word-break: break-word;
                        }

                        .company-fit-col {
                            width: 1%;
                            white-space: nowrap;
                        }

                        .company-equal-max-col {
                            width: 22%;
                            max-width: 22%;
                            min-width: 220px;
                        }

                        .company-status-badge {
                            display: inline-block;
                            min-width: 74px;
                        }

                        .company-action-col {
                            width: 95px;
                            min-width: 95px;
                            white-space: nowrap;
                        }

                        .company-action-wrap {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            gap: 4px;
                        }

                        .company-action-wrap .btn {
                            width: 30px;
                            height: 30px;
                            padding: 0;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
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

                    <table id="dataTableExample" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="company-fit-col"><?php echo display('sl') ?></th>
                                <th class="text-center company-fit-col"><?php echo display('name') ?></th>
                                <th class="text-center company-equal-max-col">Header Text</th>
                                <th class="text-center company-equal-max-col"><?php echo display('footer_text') ?></th>
                                <th class="text-center"><?php echo display('address') ?></th>
                                <th class="text-center company-fit-col">Instance</th>
                                <th class="text-center company-fit-col"><?php echo display('status') ?></th>
                                <th class="text-center company-action-col"><?php echo display('action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($companys) {
                                $sl = 1;
                                foreach ($companys as $company) {
                                    // Define badge colors for each instance type
                                    $badge_class = 'default';
                                    switch ($company->instance_type) {
                                        case 'DEV':
                                            $badge_class = 'info';
                                            break;
                                        case 'UAT':
                                            $badge_class = 'warning';
                                            break;
                                        case 'PROD':
                                            $badge_class = 'success';
                                            break;
                                        case 'BETA':
                                            $badge_class = 'primary';
                                            break;
                                        case 'LIVE':
                                            $badge_class = 'danger';
                                            break;
                                    }
                            ?>

                                    <tr>
                                        <td class="company-fit-col"><?php echo $sl; ?></td>
                                        <td class="company-name-nowrap company-fit-col"><?php echo $company->company_name ?></td>
                                        <td class="company-text-cell company-equal-max-col"><?php echo !empty($company->header_text) ? $company->header_text : 'N/A'; ?></td>
                                        <td class="company-text-cell company-equal-max-col"><?php echo !empty($company->footer_text) ? $company->footer_text : 'N/A'; ?></td>
                                        <td class="company-text-cell"><?php echo $company->address ?></td>
                                        <td class="text-center company-fit-col">
                                            <span class="badge instance-<?php echo strtolower($company->instance_type ?: 'na'); ?>">
                                                <?php echo $company->instance_type ?: 'N/A'; ?>
                                            </span>
                                        </td>
                                        <td class="text-center company-fit-col">
                                            <?php if ($company->status == 1) { ?>
                                                <span class="label label-success company-status-badge"><?php echo display('active') ?></span>
                                            <?php } else { ?>
                                                <span class="label label-danger company-status-badge"><?php echo display('inactive') ?></span>
                                            <?php } ?>
                                        </td>
                                        <td class="text-center company-action-col">
                                            <div class="company-action-wrap">
                                                <?php echo form_open() ?>
                                                <?php if ($this->permission1->method('manage_company', 'update')->access()) { ?>
                                                    <a href="<?php echo base_url() . 'edit_company/' . $company->company_id; ?>"
                                                        class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="left"
                                                        title="" data-original-title="<?php echo display('update') ?>"><i
                                                            class="fa fa-pencil" aria-hidden="true"></i></a>
                                                <?php } ?>
                                                <?php if (
                                                    $this->permission1->method('manage_company', 'delete')->access() &&
                                                    $company->company_id != 1 
                                                ) { ?>

                                                    <a href="<?php echo base_url('dashboard/setting/delete_company/' . $company->company_id); ?>"
                                                        class="btn btn-danger btn-sm"
                                                        data-toggle="tooltip"
                                                        data-placement="left"
                                                        title="<?php echo display('delete'); ?>"
                                                        onclick="return confirm('Are you sure you want to delete this company?');">

                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>

                                                <?php } ?>
                                                <?php echo form_close() ?>
                                            </div>
                                        </td>
                                    </tr>

                            <?php
                                    $sl++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>