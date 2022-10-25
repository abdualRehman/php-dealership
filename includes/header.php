<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&amp;family=Roboto+Mono&amp;display=swap" rel="stylesheet">
    <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-core.css" rel="stylesheet">
    <link href="<?php echo $GLOBALS['siteurl']; ?>/assets/build/styles/ltr-vendor.css" rel="stylesheet">

    <style>
        body.aside-active.aside-desktop-minimized .sticky-wrapper.is-sticky .header-container.container-fluid {
            padding-left: 1rem !important;
        }

        body.aside-desktop-minimized .aside.aside-hover:hover {
            height: 100% !important;
        }

        body.aside-desktop-minimized .aside.aside-hover .aside-body,
        body.aside-desktop-minimized .aside.aside-hover .aside-addon {
            display: none !important;
        }

        body.aside-desktop-minimized .aside.aside-hover:hover .aside-body,
        body.aside-desktop-minimized .aside.aside-hover:hover .aside-addon {
            display: block !important;
        }

        body.aside-desktop-minimized .aside.aside-hover:not(:hover) .aside-body,
        body.aside-desktop-minimized .aside.aside-hover:not(:hover) .aside-addon {
            display: none !important;
        }

        .menu-section .menu-section-icon {
            display: none !important;
        }

        body.aside-active.aside-desktop-minimized .wrapper {
            padding-left: 0px !important;
        }

        @media (min-width: 1025px) {
            body.aside-desktop-minimized .aside {
                width: 0.1rem;
                transition: all .2s ease-in-out;
            }

            body.aside-active.aside-desktop-minimized .sticky-header {
                left: 0rem !important;
            }
        }

        #sidemenu-todo.sidemenu-wider {
            width: 30rem;
        }

        .link-css {
            font-size: inherit;
            text-decoration: underline;
            cursor: pointer;
            font-weight: bold;
            text-transform: capitalize;
        }

        #sidemenu-todo.sidemenu-wider.expanded {
            width: 95% !important;
        }

        .font-initial {
            font-size: initial !important;
            text-decoration: underline;
        }

        .text-overflow-ellipsis {
            display: inline-block;
            width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .responsive-content {
                font-size: 10px;
                margin-top: 2px;
                margin-bottom: 2px;
            }
        }
    </style>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-lightbox/0.2.12/slick-lightbox.css" rel="stylesheet" />
    <!-- <link href="https://dashboard1.panely-html.blueupcode.com/assets/images/favicon.ico" rel="shortcut icon"
        type="image/x-icon"> -->

    <link href="https://cdn.datatables.net/rowgroup/1.0.2/css/rowGroup.dataTables.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo $GLOBALS['siteurl']; ?>/custom/css/responsive.css" rel="stylesheet">
    <title>Dashboard</title>
</head>

<body class="theme-light preload-active aside-active aside-mobile-minimized aside-desktop-maximized" id="fullscreen">
    <?php
    // check permission for todays availibilty
    if (hasAccess("todayavail", "Edit") === 'false') {
        echo '<input type="hidden" name="isAvailibilityEditable" id="isAvailibilityEditable" value="false" />';
    } else {
        echo '<input type="hidden" name="isAvailibilityEditable" id="isAvailibilityEditable" value="true" />';
    }

    ?>
    <div class="preload">
        <div class="preload-dialog">
            <div class="spinner-border text-primary preload-spinner"></div>
        </div>
    </div>
    <div class="holder">
        <div class="aside">
            <div class="aside-header">
                <h3 class="aside-title">One Dealers</h3>
                <div class="aside-addon"><button class="btn btn-label-primary btn-icon btn-lg" data-toggle="aside">
                        <i class="fa fa-times aside-icon-minimize"></i>
                        <i class="fa fa-bars aside-icon-maximize"></i>
                    </button>
                </div>
            </div>
            <div class="aside-body" data-simplebar="data-simplebar">
                <div class="menu">
                    <?php
                    if (
                        hasAccess("matrix", "View") !== 'false' ||
                        hasAccess("swap", "Add") !== 'false' || hasAccess("swap", "Edit") !== 'false' || hasAccess("swap", "Remove") !== 'false' ||
                        hasAccess("incentives", "View") !== 'false'
                    ) {
                    ?>

                        <div class="menu-item"><button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-plus"></i>
                                </div>
                                <span class="menu-item-text">New Cars</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php
                                if (hasAccess("incentives", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/incentives.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Incentive</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("matrix", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Matrix</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("swap", "Add") !== 'false' || hasAccess("swap", "Edit") !== 'false' || hasAccess("swap", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/swaps.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Swaps</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    if (hasAccess("inventory", "View") !== 'false') {
                    ?>
                        <div class="menu-item"><button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-clipboard"></i>
                                </div>
                                <span class="menu-item-text">Inventory Management</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php
                                if (hasAccess("inventory", "Add") !== 'false') {
                                ?>

                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/inventory/addInventory.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Add / Import</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("inventory", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/inventory/manageInv.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Manage</span>
                                        </a>
                                    </div>
                                <?php
                                }

                                ?>
                            </div>
                        </div>

                    <?php
                    }
                    ?>
                    <?php

                    if (
                        hasAccess("sale", "Add") !== 'false'  ||
                        hasAccess("sale", "View") !== 'false' ||
                        hasAccess("todo", "View") !== 'false' ||
                        hasAccess("regp", "View") !== 'false'

                    ) {
                    ?>

                        <div class="menu-item"><button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-shopping-cart"></i>
                                </div>
                                <span class="menu-item-text">Sales</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php
                                if (hasAccess("sale", "Add") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/addSale.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Add Sale</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("regp", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/registrationProblem.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Registration Problem</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("sale", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Sold Logs</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("todo", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldTodo.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">To Do's</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                    <?php
                    }
                    ?>
                    <?php
                    if (
                        hasAccess("user", "View") !== 'false' ||
                        hasAccess("role", "Add") !== 'false' || hasAccess("role", "Edit") !== 'false' || hasAccess("role", "Remove") !== 'false'
                    ) {
                    ?>

                        <div class="menu-item"><button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <span class="menu-item-text">Users Management</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php
                                if (hasAccess("user", "Add") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/users/addUser.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Add User</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("role", "Add") !== 'false' || hasAccess("role", "Edit") !== 'false' || hasAccess("role", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/users/roleList.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Role List</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("user", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/users/userList.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">User List</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                    <?php
                    }
                    if (
                        hasAccess("swploc", "View") !== 'false' ||
                        hasAccess("bodyshops", "View") !== 'false' ||
                        hasAccess("dealership", "View") !== 'false'
                    ) {
                    ?>
                        <div class="menu-item">
                            <button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-address-book"></i>
                                </div>
                                <span class="menu-item-text">Contacts</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php

                                if (hasAccess("bodyshops", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/bodyshops.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Bodyshop Contacts</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("dealership", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/dealershipContacts.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text"> Dealership Contacts</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if ($_SESSION['userRole'] === 'Admin') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/userLocations.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Locations</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("swploc", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/locations.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Swap Locations</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>

                            </div>
                        </div>


                    <?php
                    }
                    if (
                        hasAccess("incr", "View") !== 'false' ||
                        hasAccess("sptr", "View") !== 'false' ||
                        hasAccess("manprice", "View") !== 'false' ||
                        hasAccess("matrixfile", "Add") !== 'false' ||
                        hasAccess("matrixrule", "View") !== 'false' ||
                        hasAccess("bdcrule", "View") !== 'false' ||
                        hasAccess("raterule", "View") !== 'false' ||
                        hasAccess("leaserule", "View") !== 'false' ||
                        hasAccess("cashincrule", "View") !== 'false' ||
                        hasAccess("rdr", "Edit") !== 'false' || hasAccess("writedown", "View") !== 'false'
                    ) {
                    ?>

                        <div class="menu-item">
                            <button class="menu-item-link menu-item-toggle">
                                <div class="menu-item-icon">
                                    <i class="fa fa-solid fa-wrench"></i>
                                </div>
                                <span class="menu-item-text">Settings</span>
                                <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                            </button>
                            <div class="menu-submenu">
                                <?php
                                if (hasAccess("bdcrule", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/bdcRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">BDC Price Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("cashincrule", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/cashIncentiveRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Dealer Cash Incentive Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("incr", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/incentiveRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Incentive Rules</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("manprice", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/manufaturePrice.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Import Manufacture Price</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("leaserule", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/leaseRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Lease Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("matrixfile", "Add") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/matrixFiles.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Matrix Files</span>
                                        </a>
                                    </div>

                                <?php
                                }
                                if (hasAccess("matrixrule", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/matrixRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Matrix Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("raterule", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/rateRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Rate Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("rdr", "Edit") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/rdrRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">RDR Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("sptr", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/salesPesonsRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Sales Person's Todo Rules</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("writedown", "View") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/writeDownRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Write Down Rules</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                    <br><br>

                </div>
            </div>
        </div>
        <div class="wrapper">
            <div class="header">
                <div class="header-holder header-holder-desktop sticky-header" id="sticky-header-desktop">
                    <div class="header-container container-fluid">
                        <div class="header-wrap">
                            <ul class="nav nav-pills">
                                <li class="nav-item" id="tablet-menu">
                                    <button class="btn btn-flat-primary btn-icon" data-toggle="aside"><i class="fa fa-bars"></i></button>
                                </li>
                                <li class="nav-item">
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/dashboard.php" id="dashboard" class="nav-link active">Dashboard</a>
                                </li>
                                <?php
                                if (hasAccess("lotWizards", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/wizard/lotwizards.php" id="lotWizars" class="nav-link">Lot Wizards</a>
                                    </li>
                                <?php
                                }

                                if (hasAccess("usedCars", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/wizard/usedCars.php" id="usedCars" class="nav-link">Used Cars</a>
                                    </li>
                                <?php
                                }
                                if (hasAccess("bdc", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/more/bdc.php" id="bdcPage" class="nav-link">BDC</a>
                                    </li>
                                <?php
                                }
                                if (hasAccess("writedown", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/more/writedown.php" id="writedownPage" class="nav-link">Writedown</a>
                                    </li>
                                <?php
                                }
                                if (
                                    hasAccess("appointment", "View") !== 'false' ||
                                    hasAccess("wizardsBill", "View") !== 'false' ||
                                    hasAccess("rdr", "View") !== 'false' ||
                                    hasAccess("tansptDmg", "View") !== 'false' ||
                                    hasAccess("tansptBill", "View") !== 'false' ||
                                    hasAccess("warranty", "View") !== 'false'
                                ) {
                                ?>
                                    <li class="nav-item dropdown">
                                        <a href="#" class="nav-link" id="more" data-toggle="dropdown">More</a>
                                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-wide dropdown-menu-animated overflow-hidden">
                                            <div class="dropdown-row">
                                                <?php
                                                if (hasAccess("appointment", "View") !== 'false') {
                                                ?>
                                                    <div class="dropdown-col">
                                                        <h4 class="dropdown-header dropdown-header-lg">Appointments</h4>
                                                        <div class="grid-nav grid-nav-action">
                                                            <div class="grid-nav-row">
                                                                <a href="<?php echo  $GLOBALS['siteurl']; ?>/more/appointments.php" class="grid-nav-item">
                                                                    <div class="grid-nav-icon">
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </div>
                                                                    <span class="grid-nav-content">Appointment Calendar</span>
                                                                </a>
                                                                <a href="<?php echo  $GLOBALS['siteurl']; ?>/more/deliveryCoordinators.php" class="grid-nav-item">
                                                                    <div class="grid-nav-icon">
                                                                        <i class="far fa-clipboard"></i>
                                                                    </div>
                                                                    <span class="grid-nav-content">Delivery Coordinator</span>
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                if (
                                                    hasAccess("wizardsBill", "View") !== 'false' ||
                                                    hasAccess("rdr", "View") !== 'false' ||
                                                    hasAccess("tansptDmg", "View") !== 'false' ||
                                                    hasAccess("tansptBill", "View") !== 'false' ||
                                                    hasAccess("warranty", "View") !== 'false'
                                                ) {
                                                ?>
                                                    <div class="dropdown-col border-left">
                                                        <h4 class="dropdown-header dropdown-header-lg">Tools</h4>
                                                        <?php
                                                        if (hasAccess("wizardsBill", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/lotwizardsBill.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Lot Wizards Bills</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("rdr", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/rdr.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">RDR (RETAIL DELIVERY REGISTRATION)</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("tansptDmg", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportation.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Damage</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("tansptBill", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportationBills.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Bills</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("warranty", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/warrantyCancellation.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Warranty Cancellation</span>
                                                            </a>
                                                        <?php
                                                        }

                                                        ?>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="header-wrap header-wrap-block">
                            <div class="input-group-icon input-group-lg widget15-compact d-none" id="searchBar">
                                <div class="input-group-prepend">
                                    <i class="fa fa-search text-primary"></i>
                                </div>
                                <input type="text" class="form-control" name="searchcars" id="searchcars" placeholder="Type to search...">
                            </div>
                            <div class="input-group-icon input-group-lg widget15-compact" id="statusBar">
                                <h3 class="portlet-title">
                                    <?php
                                    if (hasAccess("sale", "View") !== 'false') {
                                    ?>
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary todaySoldStatus" id="todaySoldStatus">
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="header-wrap">
                            <ul class="nav nav-pills">
                                <?php
                                if (hasAccess("matrix", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=man" id="matrixPage" class="nav-link">
                                            Matrix
                                        </a>
                                    </li>
                                <?php
                                }
                                if (hasAccess("sale", "Add") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/addSale.php" id="addSalePage" class="nav-link">
                                            Add Sale
                                        </a>
                                    </li>
                                <?php
                                }
                                if (hasAccess("sale", "View") !== 'false') {
                                ?>
                                    <li class="nav-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man" id="soldLogsPage" class="nav-link">
                                            Sold Logs
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                            <?php
                            if (hasAccess("todayavail", "View") !== 'false') {
                            ?>
                                <button class="btn btn-label-primary btn-icon mr-2" data-toggle="sidemenu" data-target="#sidemenu-todo" onclick="loadSchedules()">
                                    <i class="far fa-calendar-alt"></i>
                                </button>
                            <?php
                            }
                            if (hasAccess("weblink", "View") !== 'false') {
                            ?>
                                <div class="dropdown">
                                    <button class="btn btn-label-primary btn-icon" data-toggle="dropdown" onclick="loadWebLinks()">
                                        <i class="fa fa-globe"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-wide overflow-hidden py-0">
                                        <div class="portlet border-0 portlet-scroll">
                                            <div class="portlet-header bg-primary rounded-0">
                                                <div class="portlet-icon text-white"><i class="fa fa-globe"></i></div>
                                                <h3 class="portlet-title text-white">Websites Links</h3>
                                                <?php
                                                if (hasAccess("weblink", "Add") !== 'false') {
                                                    echo '<button class="btn portlet-icon text-white" style="cursor:pointer" data-toggle="modal" data-target="#addWebsiteModal"><i class="fa fa-plus"></i></button>';
                                                }
                                                ?>
                                            </div>
                                            <div class="portlet-body p-0 rounded-0" data-toggle="simplebar">
                                                <div class="rich-list rich-list-action webLinksList" id="webLinksList">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="dropdown ml-2" id="notificationDropdown">
                                <button class="btn btn-label-primary btn-icon mr-1" data-toggle="dropdown">
                                    <i class="far fa-bell"></i>
                                    <div class="btn-marker d-none">
                                        <span class="badge badge-secondary btn-counter">0</span>
                                    </div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right notification-menu dropdown-menu-wide overflow-hidden py-0">
                                    <div class="portlet border-0 portlet-scroll">
                                        <div class="portlet-header bg-primary rounded-0">
                                            <div class="portlet-icon text-white "><i class="far fa-bell"></i></div>
                                            <h3 class="portlet-title text-white">Notifications</h3>
                                            <button class="btn btn-icon btn-sm btn-danger" onclick="remove_notification()"><i class="fa fa-trash"></i></button>
                                        </div>
                                        <div class="portlet-body p-0 rounded-0 notification-list" data-toggle="simplebar">
                                            <div class="rich-list rich-list-action">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown ml-2"><button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                                    <div class="widget13-text">Hi <strong> <?php echo $_SESSION['userName']; ?> </strong></div>
                                    <div class="avatar avatar-info widget13-avatar">
                                        <div class="avatar-display" style="width: inherit;height: inherit;">
                                            <?php
                                            if ($_SESSION['userProfile'] != "") {
                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/' . $_SESSION['userProfile'] . '" alt="Avatar image">';
                                            } else {
                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/default.jpg" alt="Avatar image" >';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-wide dropdown-menu-right dropdown-menu-animated overflow-hidden py-0">
                                    <div class="portlet border-0">
                                        <div class="portlet-header bg-primary rounded-0">
                                            <div class="rich-list-item w-100 p-0">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-circle">
                                                        <div class="avatar-display" style="width: inherit;height: inherit;">
                                                            <?php
                                                            if ($_SESSION['userProfile'] != "") {
                                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/' . $_SESSION['userProfile'] . '" alt="Avatar image">';
                                                            } else {
                                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/default.jpg" alt="Avatar image" >';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h3 class="rich-list-title text-white"> <?php echo $_SESSION['userName']; ?> </h3><span class="rich-list-subtitle text-white"> <?php echo $_SESSION['userRoleName']; ?> </span>
                                                </div>
                                                <div class="rich-list-append">
                                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/logout.php" class="btn btn-danger">Sign out</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body p-0">
                                            <div class="grid-nav grid-nav-flush grid-nav-action grid-nav-no-rounded">
                                                <div class="grid-nav-row">
                                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/users/profile.php" class="grid-nav-item">
                                                        <div class="grid-nav-icon">
                                                            <i class="far fa-address-card"></i>
                                                        </div>
                                                        <span class="grid-nav-content">Profile Setting</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-holder header-holder-mobile sticky-header" id="sticky-header-mobile">
                    <div class="header-container container-fluid">
                        <div class="header-wrap"><button class="btn btn-flat-primary btn-icon" data-toggle="aside"><i class="fa fa-bars"></i></button></div>
                        <div class="header-wrap header-wrap-block justify-content-start p-2 text-center d-contents text-overflow-ellipsis">
                            <!-- <h4 class="header-brand">One Dealers</h4> -->
                            <h5 class="portlet-title">
                                <?php
                                if (hasAccess("sale", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary todaySoldStatus">
                                    </a>
                                <?php
                                }
                                ?>
                            </h5>
                        </div>
                        <div class="header-wrap">
                            <div class="dropdown ml-2"><button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                                    <!-- <div class="widget13-text">Hi <strong> <?php //echo $_SESSION['userName']; 
                                                                                ?> </strong></div> -->
                                    <div class="avatar avatar-info widget13-avatar">
                                        <div class="avatar-display" style="width: inherit;height: inherit;">
                                            <?php
                                            if ($_SESSION['userProfile'] != "") {
                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/' . $_SESSION['userProfile'] . '" alt="Avatar image">';
                                            } else {
                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/default.jpg" alt="Avatar image" >';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </button>


                                <div class="dropdown-menu dropdown-menu-wide dropdown-menu-right dropdown-menu-animated overflow-hidden py-0">
                                    <div class="portlet border-0">
                                        <div class="portlet-header bg-primary rounded-0">
                                            <div class="rich-list-item w-100 p-0">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-circle">
                                                        <div class="avatar-display" style="width: inherit;height: inherit;">
                                                            <?php
                                                            if ($_SESSION['userProfile'] != "") {
                                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/' . $_SESSION['userProfile'] . '" alt="Avatar image">';
                                                            } else {
                                                                echo '<img src="' . $GLOBALS['siteurl'] . '/assets/profiles/default.jpg" alt="Avatar image" >';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h3 class="rich-list-title text-white"> <?php echo $_SESSION['userName']; ?> </h3><span class="rich-list-subtitle text-white"> <?php echo $_SESSION['userRoleName']; ?> </span>
                                                </div>
                                                <div class="rich-list-append"><a href="<?php echo $GLOBALS['siteurl']; ?>/logout.php" class="btn btn-danger">Sign out</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body p-0">
                                            <div class="grid-nav grid-nav-flush grid-nav-action grid-nav-no-rounded">
                                                <div class="grid-nav-row">
                                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/users/profile.php" class="grid-nav-item">
                                                        <div class="grid-nav-icon">
                                                            <i class="far fa-address-card"></i>
                                                        </div>
                                                        <span class="grid-nav-content">Profile Setting</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-holder header-holder-mobile">
                    <div class="header-container container-fluid">
                        <div class="row" style="width:-webkit-fill-available;">
                            <div class="col-9">
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/dashboard.php" id="dashboard" class="btn btn-flat-primary responsive-content breadcrumb-text">Dashboard</a>
                                <?php
                                if (hasAccess("lotWizards", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/wizard/lotwizards.php" id="lotWizars" class="btn btn-flat-primary responsive-content breadcrumb-text">Lot Wizards</a>
                                <?php
                                }
                                if (hasAccess("usedCars", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/wizard/usedCars.php" id="usedCars" class="btn btn-flat-primary responsive-content breadcrumb-text">Used Cars</a>
                                <?php
                                }
                                if (hasAccess("sale", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man" class="btn btn-flat-primary responsive-content breadcrumb-text">Sold Logs</a>
                                <?php
                                }
                                if (hasAccess("matrix", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=man" class="btn btn-flat-primary responsive-content breadcrumb-text">Matrix</a>
                                <?php
                                }
                                if (hasAccess("writedown", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/more/writedown.php" class="btn btn-flat-primary responsive-content breadcrumb-text">Writedown</a>
                                <?php
                                }
                                if (hasAccess("bdc", "View") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/more/bdc.php" class="btn btn-flat-primary responsive-content breadcrumb-text">BDC</a>
                                <?php
                                }
                                if (hasAccess("sale", "Add") !== 'false') {
                                ?>
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/addSale.php" class="btn btn-flat-primary responsive-content breadcrumb-text">Add Sale</a>
                                <?php
                                }
                                if (
                                    hasAccess("appointment", "View") !== 'false' ||
                                    hasAccess("wizardsBill", "View") !== 'false' ||
                                    hasAccess("rdr", "View") !== 'false' ||
                                    hasAccess("tansptDmg", "View") !== 'false' ||
                                    hasAccess("tansptBill", "View") !== 'false' ||
                                    hasAccess("warranty", "View") !== 'false'
                                ) {
                                ?>
                                    <div class="dropdown">
                                        <a href="#" class="btn btn-flat-primary responsive-content breadcrumb-text" id="more" data-toggle="dropdown">More</a>
                                        <div class="dropdown-menu dropdown-menu-left dropdown-menu-wide dropdown-menu-animated overflow-hidden">
                                            <div class="dropdown-row flex-column">
                                                <?php
                                                if (hasAccess("appointment", "View") !== 'false') {
                                                ?>
                                                    <div class="dropdown-col">
                                                        <h4 class="dropdown-header dropdown-header-lg">Appointments</h4>
                                                        <div class="grid-nav grid-nav-action">
                                                            <div class="grid-nav-row">
                                                                <a href="<?php echo  $GLOBALS['siteurl']; ?>/more/appointments.php" class="grid-nav-item">
                                                                    <div class="grid-nav-icon">
                                                                        <i class="far fa-calendar-alt"></i>
                                                                    </div>
                                                                    <span class="grid-nav-content">Appointment Calendar</span>
                                                                </a>
                                                                <a href="<?php echo  $GLOBALS['siteurl']; ?>/more/deliveryCoordinators.php" class="grid-nav-item">
                                                                    <div class="grid-nav-icon">
                                                                        <i class="far fa-clipboard"></i>
                                                                    </div>
                                                                    <span class="grid-nav-content">Delivery Coordinator</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                if (
                                                    hasAccess("wizardsBill", "View") !== 'false' ||
                                                    hasAccess("rdr", "View") !== 'false' ||
                                                    hasAccess("tansptDmg", "View") !== 'false' ||
                                                    hasAccess("tansptBill", "View") !== 'false' ||
                                                    hasAccess("warranty", "View") !== 'false'
                                                ) {

                                                ?>

                                                    <div class="dropdown-col border-left">
                                                        <h4 class="dropdown-header dropdown-header-lg">Tools</h4>
                                                        <?php
                                                        if (hasAccess("wizardsBill", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/lotwizardsBill.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Lot Wizards Bills</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("rdr", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/rdr.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">RDR (RETAIL DELIVERY REGISTRATION)</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("tansptDmg", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportation.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Damage</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("tansptBill", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportationBills.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Bills</span>
                                                            </a>
                                                        <?php
                                                        }
                                                        if (hasAccess("warranty", "View") !== 'false') {
                                                        ?>
                                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/more/warrantyCancellation.php" class="dropdown-item">
                                                                <i class="dropdown-bullet"></i> <span class="dropdown-content">Warranty Cancellation</span>
                                                            </a>
                                                        <?php
                                                        }

                                                        ?>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                            <div class="col-3">
                                <div class="d-flex align-items-center justify-content-end">
                                    <?php
                                    if (hasAccess("todayavail", "View") !== 'false') {
                                    ?>
                                        <button class="btn btn-label-primary btn-icon mr-2" data-toggle="sidemenu" data-target="#sidemenu-todo" onclick="loadSchedules()">
                                            <i class="far fa-calendar-alt"></i>
                                        </button>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if (hasAccess("weblink", "View") !== 'false') {
                                    ?>
                                        <div class="dropdown">
                                            <button class="btn btn-label-primary btn-icon" data-toggle="dropdown" onclick="loadWebLinks()">
                                                <i class="fa fa-globe"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-wide overflow-hidden py-0">
                                                <div class="portlet border-0 portlet-scroll">
                                                    <div class="portlet-header bg-primary rounded-0">
                                                        <div class="portlet-icon text-white"><i class="fa fa-globe"></i></div>
                                                        <h3 class="portlet-title text-white">Websites Links</h3>
                                                        <?php
                                                        if (hasAccess("weblink", "Add") !== 'false') {
                                                            echo '<button class="btn portlet-icon text-white" style="cursor:pointer" data-toggle="modal" data-target="#addWebsiteModal"><i class="fa fa-plus"></i></button>';
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="portlet-body p-0 rounded-0" data-toggle="simplebar">
                                                        <div class="rich-list rich-list-action webLinksList" id="webLinksList">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="dropdown ml-2" id="notificationDropdown">
                                        <button class="btn btn-label-primary btn-icon mr-1" data-toggle="dropdown">
                                            <i class="far fa-bell"></i>
                                            <div class="btn-marker d-none">
                                                <span class="badge badge-secondary btn-counter">0</span>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right notification-menu dropdown-menu-wide overflow-hidden py-0">
                                            <div class="portlet border-0 portlet-scroll">
                                                <div class="portlet-header bg-primary rounded-0">
                                                    <div class="portlet-icon text-white "><i class="far fa-bell"></i></div>
                                                    <h3 class="portlet-title text-white">Notifications</h3>
                                                    <button class="btn btn-icon btn-sm btn-danger" onclick="remove_notification()"><i class="fa fa-trash"></i></button>
                                                </div>
                                                <div class="portlet-body p-0 rounded-0 notification-list" data-toggle="simplebar">
                                                    <div class="rich-list rich-list-action">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>