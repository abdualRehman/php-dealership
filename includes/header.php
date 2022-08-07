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
        .aside.aside-hover {
            height: 100%;
        }

        body.aside-active.aside-desktop-minimized .sticky-wrapper.is-sticky .header-container.container-fluid {
            padding-left: 1rem !important;
        }

        .aside.aside-hover:hover {
            height: 100% !important;
        }

        .aside.aside-hover .aside-body,
        .aside.aside-hover .aside-addon {
            display: none !important;
        }

        .aside.aside-hover:hover .aside-body,
        .aside.aside-hover:hover .aside-addon {
            display: block !important;
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
                left: 0rem;
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
    </style>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/slick-lightbox/0.2.12/slick-lightbox.css" rel="stylesheet" />
    <!-- <link href="https://dashboard1.panely-html.blueupcode.com/assets/images/favicon.ico" rel="shortcut icon"
        type="image/x-icon"> -->

    <link href="https://cdn.datatables.net/rowgroup/1.0.2/css/rowGroup.dataTables.min.css" rel="stylesheet" type="text/css" />

    <title>Dashboard</title>
</head>

<body class="theme-light preload-active aside-active aside-mobile-minimized aside-desktop-maximized" id="fullscreen">
    <div class="preload">
        <div class="preload-dialog">
            <div class="spinner-border text-primary preload-spinner"></div>
        </div>
    </div>
    <div class="holder">
        <div class="aside">
            <div class="aside-header">
                <h3 class="aside-title">Logo</h3>
                <div class="aside-addon"><button class="btn btn-label-primary btn-icon btn-lg" data-toggle="aside">
                        <i class="fa fa-times aside-icon-minimize"></i>
                        <!-- <i class="fa fa-thumbtack aside-icon-maximize"></i> -->
                        <i class="fa fa-bars aside-icon-maximize"></i>
                    </button>
                </div>
            </div>
            <div class="aside-body" data-simplebar="data-simplebar">
                <div class="menu">
                    <div class="menu-item"><a href="<?php echo $GLOBALS['siteurl']; ?>/index.php" data-menu-path="/ltr/index.php" class="menu-item-link">
                            <div class="menu-item-icon"><i class="fa fa-desktop"></i></div><span class="menu-item-text">Dashboard</span>
                            <div class="menu-item-addon"><span class="badge badge-success">New</span></div>
                        </a>
                    </div>
                    <!-- <div class="menu-section">
                        <div class="menu-section-icon"><i class="fa fa-ellipsis-h"></i></div>
                        <h2 class="menu-section-text">Inventory</h2>
                    </div> -->
                    <?php
                    if (
                        hasAccess("matrix", "View") !== 'false' ||
                        hasAccess("swap", "Add") !== 'false' || hasAccess("swap", "Edit") !== 'false' || hasAccess("swap", "Remove") !== 'false' ||
                        hasAccess("incentives", "Edit") !== 'false'
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
                                if (hasAccess("incentives", "Edit") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/incentives.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">incentive</span>
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
                        hasAccess("inventory", "Add") !== 'false' ||
                        hasAccess("inventory", "Edit") !== 'false' || hasAccess("inventory", "Remove") !== 'false'
                    ) {
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
                                if (hasAccess("inventory", "Edit") !== 'false' || hasAccess("inventory", "Remove") !== 'false') {
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


                    <!-- <div class="menu-section">
                        <div class="menu-section-icon"><i class="fa fa-ellipsis-h"></i></div>
                        <h2 class="menu-section-text">Sales</h2>
                    </div> -->

                    <?php

                    if (
                        hasAccess("sale", "Add") !== 'false'  ||
                        hasAccess("sale", "View") !== 'false' ||
                        hasAccess("todo", "Edit") !== 'false' ||
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
                                // if (hasAccess("sale", "Edit") !== 'false' || hasAccess("sale", "Remove") !== 'false' || $salesConsultantID == $_SESSION['userRole']) {
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
                                if (hasAccess("todo", "Edit") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldTodo.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">To Do's</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                // if (hasAccess("regp", "Add") !== 'false'  || hasAccess("regp", "Edit") !== 'false' || hasAccess("regp", "Remove") !== 'false') {
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
                                ?>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                    <!-- <div class="menu-section">
                        <div class="menu-section-icon"><i class="fa fa-ellipsis-h"></i></div>
                        <h2 class="menu-section-text">Users</h2>
                    </div> -->

                    <?php
                    if (
                        hasAccess("user", "Add") !== 'false'  ||
                        hasAccess("user", "Edit") !== 'false' || hasAccess("user", "Remove") !== 'false' ||
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
                                if (hasAccess("user", "Edit") !== 'false' || hasAccess("user", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/users/userList.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">User List</span>
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
                                ?>
                            </div>
                        </div>

                    <?php
                    }
                    ?>

                    <!-- <div class="menu-section">
                        <div class="menu-section-icon"><i class="fa fa-ellipsis-h"></i></div>
                        <h2 class="menu-section-text">Settings</h2>
                    </div> -->

                    <?php
                    if (
                        hasAccess("incr", "Add") !== 'false' || hasAccess("incr", "Edit") !== 'false' || hasAccess("incr", "Remove") !== 'false' ||
                        hasAccess("sptr", "Add") !== 'false' || hasAccess("sptr", "Edit") !== 'false' || hasAccess("sptr", "Remove") !== 'false' ||
                        hasAccess("swploc", "Add") !== 'false' || hasAccess("swploc", "Edit") !== 'false' || hasAccess("swploc", "Remove") !== 'false' ||
                        hasAccess("manprice", "Add") !== 'false' || hasAccess("manprice", "Edit") !== 'false' || hasAccess("manprice", "Remove") !== 'false' ||
                        hasAccess("bodyshops", "Add") !== 'false' || hasAccess("bodyshops", "Edit") !== 'false' || hasAccess("bodyshops", "Remove") !== 'false' ||
                        hasAccess("matrixfile", "Add") !== 'false' ||
                        hasAccess("matrixrule", "Add") !== 'false' || hasAccess("matrixrule", "Edit") !== 'false' || hasAccess("matrixrule", "Remove") !== 'false' ||
                        hasAccess("bdcrule", "Add") !== 'false' || hasAccess("bdcrule", "Edit") !== 'false' || hasAccess("bdcrule", "Remove") !== 'false' ||
                        hasAccess("raterule", "Add") !== 'false' || hasAccess("raterule", "Edit") !== 'false' || hasAccess("raterule", "Remove") !== 'false' ||
                        hasAccess("leaserule", "Add") !== 'false' || hasAccess("leaserule", "Edit") !== 'false' || hasAccess("leaserule", "Remove") !== 'false' ||
                        hasAccess("cashincrule", "Add") !== 'false' || hasAccess("cashincrule", "Edit") !== 'false' || hasAccess("cashincrule", "Remove") !== 'false'
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

                                if (hasAccess("incr", "Add") !== 'false' || hasAccess("incr", "Edit") !== 'false' || hasAccess("incr", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/incentiveRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Incentive Rules</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("sptr", "Add") !== 'false' || hasAccess("sptr", "Edit") !== 'false' || hasAccess("sptr", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/salesPesonsRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Sales Person's Todo Rules</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("swploc", "Add") !== 'false' || hasAccess("swploc", "Edit") !== 'false' || hasAccess("swploc", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/locations.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Swap Locations</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("manprice", "Add") !== 'false' || hasAccess("manprice", "Edit") !== 'false' || hasAccess("manprice", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/manufaturePrice.php?r=man" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Import Manufacture Price</span>
                                        </a>
                                    </div>
                                <?php
                                }

                                if (hasAccess("bodyshops", "Add") !== 'false' || hasAccess("bodyshops", "Edit") !== 'false' || hasAccess("bodyshops", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/bodyshops.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Bodyshop Contacts</span>
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
                                if (hasAccess("matrixrule", "Add") !== 'false' || hasAccess("matrixrule", "Edit") !== 'false' || hasAccess("matrixrule", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/matrixRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Matrix Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("bdcrule", "Add") !== 'false' || hasAccess("bdcrule", "Edit") !== 'false' || hasAccess("bdcrule", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/bdcRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">BDC Price Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("raterule", "Add") !== 'false' || hasAccess("raterule", "Edit") !== 'false' || hasAccess("raterule", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/rateRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Rate Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("leaserule", "Add") !== 'false' || hasAccess("leaserule", "Edit") !== 'false' || hasAccess("leaserule", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/leaseRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Lease Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }
                                if (hasAccess("cashincrule", "Add") !== 'false' || hasAccess("cashincrule", "Edit") !== 'false' || hasAccess("cashincrule", "Remove") !== 'false') {
                                ?>
                                    <div class="menu-item">
                                        <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/cashIncentiveRules.php" class="menu-item-link">
                                            <i class="menu-item-bullet"></i>
                                            <span class="menu-item-text">Dealer Cash Incentive Rule</span>
                                        </a>
                                    </div>
                                <?php
                                }

                                ?>

                                <div class="menu-item">
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/settings/rdrRules.php" class="menu-item-link">
                                        <i class="menu-item-bullet"></i>
                                        <span class="menu-item-text">RDR Rule</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <!-- <div class="menu-item">
                        <button class="menu-item-link menu-item-toggle">
                            <div class="menu-item-icon">
                                <i class="fa fa-solid fa-wrench"></i>
                            </div>
                            <span class="menu-item-text">Matrix Rules</span>
                            <div class="menu-item-addon"><i class="menu-item-caret caret"></i></div>
                        </button>
                        <div class="menu-submenu">
                        </div>
                    </div> -->

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
                                ?>
                                <li class="nav-item">
                                    <a href="<?php echo $GLOBALS['siteurl']; ?>/more/bdc.php" id="bdcPage" class="nav-link">BDC</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a href="<?php // echo $GLOBALS['siteurl']; 
                                                ?>/more/appointments.php" id="bdcPage" class="nav-link">Appointment Calender</a>
                                </li> -->

                                <!-- <li class="nav-item dropdown"><a href="#" class="nav-link active" data-toggle="dropdown">Apps</a>
                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-animated"><a href="#" class="dropdown-item">
                                            <div class="dropdown-icon"><i class="fa fa-boxes"></i></div><span class="dropdown-content">Inventory Manager</span>
                                            <div class="dropdown-addon"><span class="badge badge-warning badge-pill">20</span></div>
                                        </a>
                                        <div class="dropdown-submenu"><a href="#" class="dropdown-item">
                                                <div class="dropdown-icon"><i class="fa fa-project-diagram"></i></div>
                                                <span class="dropdown-content">Project manager</span>
                                                <div class="dropdown-addon"><i class="caret"></i></div>
                                            </a>
                                            <div class="dropdown-submenu-menu dropdown-submenu-left"><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Create project</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Delete project</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Ongoing project</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Completed project</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Urgent project</span></a></div>
                                        </div>
                                        <div class="dropdown-submenu"><a href="#" class="dropdown-item">
                                                <div class="dropdown-icon"><i class="fa fa-tasks"></i></div><span class="dropdown-content">Task manager</span>
                                                <div class="dropdown-addon"><i class="caret"></i></div>
                                            </a>
                                            <div class="dropdown-submenu-menu dropdown-submenu-left"><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Show task</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Assign task</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Assign member</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Completed task</span> </a><a href="#" class="dropdown-item"><i class="dropdown-bullet"></i> <span class="dropdown-content">Urgent task</span></a></div>
                                        </div><a href="#" class="dropdown-item">
                                            <div class="dropdown-icon"><i class="fa fa-dollar-sign"></i></div><span class="dropdown-content">Invoice</span>
                                        </a>
                                        <div class="dropdown-divider"></div><a href="#" class="dropdown-item">
                                            <div class="dropdown-icon"><i class="fa fa-user-cog"></i></div><span class="dropdown-content">My Account</span>
                                        </a>
                                    </div>
                                </li> -->
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link" id="more" data-toggle="dropdown">More</a>
                                    <div class="dropdown-menu dropdown-menu-left dropdown-menu-wide dropdown-menu-animated overflow-hidden">
                                        <div class="dropdown-row">
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
                                            <div class="dropdown-col border-left">
                                                <h4 class="dropdown-header dropdown-header-lg">Tools</h4>
                                                <a href="<?php echo $GLOBALS['siteurl']; ?>/more/rdr.php" class="dropdown-item">
                                                    <i class="dropdown-bullet"></i> <span class="dropdown-content">RDR (RETAIL DELIVERY REGISTRATION)</span>
                                                </a>
                                                <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportation.php" class="dropdown-item">
                                                    <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Damage</span>
                                                </a>
                                                <a href="<?php echo $GLOBALS['siteurl']; ?>/more/lotwizardsBill.php" class="dropdown-item">
                                                    <i class="dropdown-bullet"></i> <span class="dropdown-content">Lot Wizards Bills</span>
                                                </a>
                                                <a href="<?php echo $GLOBALS['siteurl']; ?>/more/transportationBills.php" class="dropdown-item">
                                                    <i class="dropdown-bullet"></i> <span class="dropdown-content">Transportation Bills</span>
                                                </a>
                                                <a href="<?php echo $GLOBALS['siteurl']; ?>/more/warrantyCancellation.php" class="dropdown-item">
                                                    <i class="dropdown-bullet"></i> <span class="dropdown-content">Warranty Cancellation</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="header-wrap header-wrap-block">
                            <div class="input-group-icon input-group-lg widget15-compact">
                                <div class="input-group-prepend">
                                    <i class="fa fa-search text-primary"></i></div>
                                <input type="text" class="form-control" name="searchcars" id="searchcars" placeholder="Type to search...">
                            </div>
                            <!-- <div class="input-group-icon input-group-lg widget15-compact">
                                <h3 class="portlet-title">
                                    <a href="<?php // echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man&filter=today" class="link-primary" id="todaySoldStatus">
                                    </a>
                                </h3>
                            </div> -->
                        </div>
                        <div class="header-wrap">
                            <!-- <div class="dropdown">
                                <button class="btn btn-label-primary btn-icon" data-toggle="dropdown"><i class="far fa-bell"></i>
                                    <div class="btn-marker"><i class="marker marker-dot text-success"></i></div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-wide dropdown-menu-animated overflow-hidden py-0">
                                    <div class="portlet border-0 portlet-scroll">
                                        <div class="portlet-header bg-primary rounded-0">
                                            <div class="portlet-icon text-white"><i class="far fa-bell"></i></div>
                                            <h3 class="portlet-title text-white">Notification</h3>
                                            <div class="portlet-addon"><span class="badge badge-warning badge-square badge-lg">9+</span></div>
                                        </div>
                                        <div class="portlet-body p-0 rounded-0" data-toggle="simplebar">
                                            <div class="rich-list rich-list-action"><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-info">
                                                            <div class="avatar-display"><i class="fa fa-file-invoice"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">New report has been received</h4>
                                                        <span class="rich-list-subtitle">2 min ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-success">
                                                            <div class="avatar-display"><i class="fa fa-shopping-basket"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Last order was completed</h4><span class="rich-list-subtitle">1 hrs ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-danger">
                                                            <div class="avatar-display"><i class="fa fa-users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Company meeting canceled</h4><span class="rich-list-subtitle">5 hrs ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-warning">
                                                            <div class="avatar-display"><i class="fa fa-paper-plane"></i></div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">New feedback received</h4><span class="rich-list-subtitle">6 hrs ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-primary">
                                                            <div class="avatar-display"><i class="fa fa-download"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">New update was available</h4><span class="rich-list-subtitle">1 day ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-success">
                                                            <div class="avatar-display"><i class="fa fa-asterisk"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">Your password was changed</h4><span class="rich-list-subtitle">2 day ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a><a href="#" class="rich-list-item">
                                                    <div class="rich-list-prepend">
                                                        <div class="avatar avatar-label-info">
                                                            <div class="avatar-display"><i class="fa fa-user-plus"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="rich-list-content">
                                                        <h4 class="rich-list-title">New account has been registered</h4>
                                                        <span class="rich-list-subtitle">5 day ago</span>
                                                    </div>
                                                    <div class="rich-list-append"><i class="caret mx-2"></i></div>
                                                </a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown ml-2">
                                <button class="btn btn-label-primary btn-icon" data-toggle="dropdown"><i class="far fa-comments"></i>
                                    <div class="btn-marker"><i class="marker marker-dot text-success"></i></div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-wide dropdown-menu-animated overflow-hidden py-0">
                                    <div class="portlet portlet-scroll border-0">
                                        <div class="portlet-header portlet-header-bordered rounded-0">
                                            <div class="rich-list-item w-100 p-0">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-circle">
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h3 class="rich-list-title">Garrett Winters</h3><span class="rich-list-subtitle">UX Designer</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body" data-toggle="simplebar">
                                            <div class="chat">
                                                <div class="chat-item chat-item-left">
                                                    <div class="chat-content">
                                                        <p class="chat-bubble chat-bubble-primary">Lorem ipsum dolor sit
                                                            amet, consectetur adipisicing elit. Unde, eius.</p>
                                                        <p class="chat-bubble chat-bubble-primary">Lorem ipsum dolor sit
                                                            amet, consectetur adipisicing elit. Unde, eius.</p><span class="chat-time">3 min ago</span>
                                                    </div>
                                                </div>
                                                <div class="chat-item chat-item-right">
                                                    <div class="chat-content">
                                                        <p class="chat-bubble">Lorem ipsum dolor sit amet, consectetur
                                                            adipisicing elit. Unde, eius.</p><span class="chat-time">2
                                                            min ago</span>
                                                    </div>
                                                </div>
                                                <div class="chat-item chat-item-left">
                                                    <div class="chat-content">
                                                        <p class="chat-bubble chat-bubble-primary">Lorem ipsum dolor sit
                                                            amet, consectetur adipisicing elit. Unde, eius.</p><span class="chat-time">1 min ago</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-footer portlet-footer-bordered rounded-0">
                                            <div class="input-group"><input type="text" class="form-control" placeholder="Type...">
                                                <div class="input-group-append"><button class="btn btn-primary btn-icon"><i class="fa fa-paper-plane"></i></button></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <button class="btn btn-label-primary btn-icon ml-2" data-toggle="sidemenu" data-target="#sidemenu-settings">
                                <i class="far fa-list-alt"></i>
                            </button> -->
                            <?php
                            if (hasAccess("matrix", "View") !== 'false') {
                            ?>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/matrix/manMatrix.php?r=man" class="btn btn-label-primary ml-2">
                                    Matrix
                                </a>
                            <?php
                            }
                            if (hasAccess("sale", "Add") !== 'false') {
                            ?>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/addSale.php" class="btn btn-label-primary ml-2">
                                    Add Sale
                                </a>
                            <?php
                            }
                            // if (hasAccess("sale", "Edit") !== 'false' || hasAccess("sale", "Remove") !== 'false' || $salesConsultantID == $_SESSION['userRole']) {
                            if (hasAccess("sale", "View") !== 'false') {
                            ?>
                                <a href="<?php echo $GLOBALS['siteurl']; ?>/sales/soldLogs.php?r=man" class="btn btn-label-primary ml-2">
                                    Sold Logs
                                </a>
                            <?php
                            }

                            ?>
                            <button class="btn btn-label-primary btn-icon ml-2" data-toggle="sidemenu" data-target="#sidemenu-todo" onclick="loadSchedules()">
                                <i class="far fa-calendar-alt"></i>
                            </button>
                            <div class="dropdown ml-2"><button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                                    <div class="widget13-text">Hi <strong> <?php echo $_SESSION['userName']; ?> </strong></div>
                                    <div class="avatar avatar-info widget13-avatar">
                                        <div class="avatar-display" style="width: inherit;height: inherit;">
                                            <!-- <i class="fa fa-user-alt"></i> -->
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
                                                        <!-- <div class="avatar-display">
                                                            <i class="fa fa-user-alt"></i>
                                                        </div> -->
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h3 class="rich-list-title text-white"> <?php echo $_SESSION['userName']; ?> </h3><span class="rich-list-subtitle text-white"> <?php echo $_SESSION['userRoleName']; ?> </span>
                                                </div>
                                                <!-- <div class="rich-list-append"><span class="badge badge-warning badge-square badge-lg">9+</span></div> -->
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
                                                    <!-- <a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-comments"></i></div>
                                                        <span class="grid-nav-content">Messages</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-clone"></i></div>
                                                        <span class="grid-nav-content">Activities</span>
                                                    </a> -->
                                                </div>
                                                <!-- <div class="grid-nav-row"><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-calendar-check"></i>
                                                        </div><span class="grid-nav-content">Tasks</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-sticky-note"></i>
                                                        </div><span class="grid-nav-content">Notes</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-bell"></i></div>
                                                        <span class="grid-nav-content">Notification</span>
                                                    </a></div> -->
                                            </div>
                                        </div>
                                        <!-- <div class="portlet-footer portlet-footer-bordered rounded-0">
                                            <a href="<?php echo $GLOBALS['siteurl']; ?>/logout.php" class="btn btn-label-danger">Sign out</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="header-holder header-holder-desktop">
                    <div class="header-container container-fluid">
                        <h4 class="header-title">Dashboard</h4><i class="header-divider"></i>
                        <div class="header-wrap header-wrap-block justify-content-start">
                            <div class="breadcrumb"><a href="index.php" class="breadcrumb-item">
                                    <div class="breadcrumb-icon"><i data-feather="home"></i></div><span class="breadcrumb-text">Dashboard</span>
                                </a></div>
                        </div>
                        <div class="header-wrap">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons"><label class="btn btn-flat-primary active"><input type="radio" name="timeOption" id="timeOption1" checked="checked"> Today</label> <label class="btn btn-flat-primary"><input type="radio" name="timeOption" id="timeOption2">
                                    Week</label> <label class="btn btn-flat-primary"><input type="radio" name="timeOption" id="timeOption3"> Month</label></div>
                            <button class="btn btn-label-info btn-icon ml-2" id="fullscreen-trigger" data-toggle="tooltip" title="Toggle fullscreen" data-placement="left"><i class="fa fa-expand fullscreen-icon-expand"></i> <i class="fa fa-compress fullscreen-icon-compress"></i></button>
                        </div>
                    </div>
                </div> -->

                <div class="header-holder header-holder-mobile sticky-header" id="sticky-header-mobile">
                    <div class="header-container container-fluid">
                        <div class="header-wrap"><button class="btn btn-flat-primary btn-icon" data-toggle="aside"><i class="fa fa-bars"></i></button></div>
                        <div class="header-wrap header-wrap-block justify-content-start px-3">
                            <h4 class="header-brand">Logo</h4>
                        </div>
                        <div class="header-wrap"><button class="btn btn-flat-primary btn-icon" data-toggle="sidemenu" data-target="#sidemenu-todo"><i class="far fa-calendar-alt"></i></button>
                            <div class="dropdown ml-2"><button class="btn btn-flat-primary widget13" data-toggle="dropdown">
                                    <div class="widget13-text">Hi <strong>User</strong></div>
                                    <div class="avatar avatar-info widget13-avatar">
                                        <div class="avatar-display"><i class="fa fa-user-alt"></i></div>
                                    </div>
                                </button>
                                <div class="dropdown-menu dropdown-menu-wide dropdown-menu-right dropdown-menu-animated overflow-hidden py-0">
                                    <div class="portlet border-0">
                                        <div class="portlet-header bg-primary rounded-0">
                                            <div class="rich-list-item w-100 p-0">
                                                <div class="rich-list-prepend">
                                                    <div class="avatar avatar-circle">
                                                        <!-- <div class="avatar-display"><img src="https://dashboard1.panely-html.blueupcode.com/assets/images/avatar/avatar-4.webp" alt="Avatar image"></div> -->
                                                    </div>
                                                </div>
                                                <div class="rich-list-content">
                                                    <h3 class="rich-list-title text-white">Brielle Williamson</h3><span class="rich-list-subtitle text-white">Software Engineer</span>
                                                </div>
                                                <div class="rich-list-append"><span class="badge badge-warning badge-square badge-lg">9+</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="portlet-body p-0">
                                            <div class="grid-nav grid-nav-flush grid-nav-action grid-nav-no-rounded">
                                                <div class="grid-nav-row"><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-address-card"></i>
                                                        </div><span class="grid-nav-content">Profile</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-comments"></i></div>
                                                        <span class="grid-nav-content">Messages</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-clone"></i></div>
                                                        <span class="grid-nav-content">Activities</span>
                                                    </a></div>
                                                <div class="grid-nav-row"><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-calendar-check"></i>
                                                        </div><span class="grid-nav-content">Tasks</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-sticky-note"></i>
                                                        </div><span class="grid-nav-content">Notes</span>
                                                    </a><a href="#" class="grid-nav-item">
                                                        <div class="grid-nav-icon"><i class="far fa-bell"></i></div>
                                                        <span class="grid-nav-content">Notification</span>
                                                    </a></div>
                                            </div>
                                        </div>
                                        <div class="portlet-footer portlet-footer-bordered rounded-0"><a href="<?php echo $GLOBALS['siteurl']; ?>/logout.php" class="btn btn-label-danger">Sign out</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-holder header-holder-mobile">
                    <div class="header-container container-fluid">
                        <div class="header-wrap header-wrap-block justify-content-start w-100">
                            <div class="breadcrumb"><a href="index.php" class="breadcrumb-item">
                                    <div class="breadcrumb-icon"><i data-feather="home"></i></div><span class="breadcrumb-text">Dashboard</span>
                                </a></div>
                        </div>
                    </div>
                </div>
            </div>