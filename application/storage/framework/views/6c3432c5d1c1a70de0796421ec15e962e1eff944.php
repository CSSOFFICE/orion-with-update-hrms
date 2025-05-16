<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar settings-menu">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar" id="settings-scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav" id="settings-sidebar-nav">
            <ul id="sidebarnav">

                <!--main-->
                <li class="sidenav-menu-item <?php echo e($page['settingsmenu_main'] ?? ''); ?>">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-main">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.main_settings'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/general')); ?> id="settings-menu-main-general"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/company')); ?> id="settings-menu-main-company"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.company_details'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/theme')); ?> id="settings-menu-main-theme"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.theme'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/logos')); ?> id="settings-menu-main-logo"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.company_logo'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/cronjobs')); ?> id="settings-menu-main-cronjobs"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.cronjob_settings'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--clients-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-clients">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.clients'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/clients')); ?>

                                id="settings-menu-clients-general"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--categories-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-categories">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.categories'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--project-->
                        <li>
                            <a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-project" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=project&source=ext')); ?>><?php echo e(cleanLang(__('lang.projects'))); ?>

                            </a>
                        </li>
                        <!--client-->
                        <li>
                            <a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-client" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=client&source=ext')); ?>><?php echo e(cleanLang(__('lang.clients'))); ?>

                            </a></li>

                        <!--expense-->
                        <li><a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-expense" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=expense&source=ext')); ?>><?php echo e(cleanLang(__('lang.expenses'))); ?>

                            </a></li>

                        <!--invoice-->
                        <li><a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-invoice" href="app/settings/invoices"
                                data-url=<?php echo e(url('/categories?filter_category_type=invoice&source=ext')); ?>><?php echo e(cleanLang(__('lang.invoices'))); ?>

                            </a></li>

                        <!--lead-->
                        <li><a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-lead" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=lead&source=ext')); ?>><?php echo e(cleanLang(__('lang.leads'))); ?></a>
                        </li>

                        <!--Products-->
                        <!-- not being used currently-->
                        <li><a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url hidden"
                                id="settings-menu-categories-product" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=item&source=ext')); ?>><?php echo e(cleanLang(__('lang.products'))); ?>

                            </a></li>

                        <!--estimate-->
                        <li><a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-categories-estimate" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=estimate&source=ext')); ?>><?php echo e(cleanLang(__('lang.estimates'))); ?>

                            </a></li>
                    </ul>
                </li>
                <!--projects-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-projects">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.projects'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/projects/general')); ?>

                                id="settings-menu-projects-general"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/projects/staff')); ?>

                                id="settings-menu-projects-staff-permissions"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.team_permissions'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/projects/client')); ?>

                                id="settings-menu-client-permissions"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.client_permissions'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--leads-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-leads">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.leads'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/leads/general')); ?>

                                id="settings-menu-leads-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/leads/statuses')); ?>

                                id="settings-menu-leads-stages"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.lead_stages'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/sources')); ?> id="settings-menu-leads-sources"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.lead_sources'))); ?></a>
                        </li>
                    </ul>
                </li>




                <!--milestone-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-milestones">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.milestones'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/milestones/settings')); ?>

                                id="settings-menu-milestones-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/milestones/default')); ?>

                                id="settings-menu-milestones-categories"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.default_milestones'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--tasks-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-tasks">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.tasks'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/tasks')); ?> id="settings-menu-tasks-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                    </ul>
                </li>




                <!--billing-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-billing">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.sales'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/invoices')); ?>

                                id="settings-menu-billing-invoice"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.invoices'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/taxrates')); ?>

                                id="settings-menu-billing-taxrate"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.tax_rates'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/estimates')); ?>

                                id="settings-menu-billing-estimate"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.estimates'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/expenses')); ?>

                                id="settings-menu-billing-expense"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.expenses'))); ?></a>
                        </li>
                        <li class="hidden">
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/units')); ?> id="settings-menu-billing-unit"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.product_units'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--tags-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-tags">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.tags'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/tags')); ?> id="settings-menu-tags-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/tags?source=ext')); ?> id="settings-menu-tags-view"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.view_tags'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--payment gateways-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-payment-methods">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.payment_methods'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--paypal-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/paypal')); ?>

                                id="settings-menu-payment-methods-paypal"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url">Paypal</a>
                        </li>
                        <!--stripe-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/stripe')); ?>

                                id="settings-menu-payment-methods-stripe"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url">Stripe</a>
                        </li>
                        <!--bank-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/bank')); ?>

                                id="settings-menu-payment-methods-bank"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.bank'))); ?></a>
                        </li>


                    </ul>
                </li>

                <!--Email-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-email">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.email'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <!--general-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/email/general')); ?>

                                id="settings-menu-email-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <!--templates-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/email/templates')); ?>

                                id="settings-menu-email-templates"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.email_templates'))); ?></a>
                        </li>
                        <!--smtp-->
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/email/smtp')); ?> id="settings-menu-email-smtp"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.smtp_settings'))); ?></a>
                        </li>
                    </ul>
                </li>

                <!--roles-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-roles">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.user_roles'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/roles')); ?> id="settings-menu-roles-general"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                    </ul>
                </li>


                <!--tickets-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-tickets">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.tickets'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/tickets')); ?>

                                id="settings-menu-tickets-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"
                                id="settings-menu-tickets-departments" href="javascript:void(0);"
                                data-url=<?php echo e(url('/categories?filter_category_type=ticket&source=ext')); ?>><?php echo e(cleanLang(__('lang.departments'))); ?>

                            </a>

                        </li>
                    </ul>
                </li>


                <!--knowledgeebase-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-knowledgebase">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.knowledgebase'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/knowledgebase/settings')); ?>

                                id="settings-menu-knowledgebase-settings"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.general_settings'))); ?></a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-url=<?php echo e(url('/settings/knowledgebase/default')); ?>

                                id="settings-menu-knowledgebase-categories"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.categories'))); ?></a>
                        </li>
                    </ul>
                </li>


                <!--Other-->
                <li class="sidenav-menu-item">
                    <a class="has-arrow waves-effect waves-dark" href="javascript:void(0);" aria-expanded="false"
                        id="settings-menu-other">
                        <span class="hide-menu"><?php echo e(cleanLang(__('lang.other'))); ?>

                        </span>
                    </a>
                    <ul aria-expanded="false" class="collapse" id="settings-menu-other">
                        <li><a href="javascript:void(0);" data-url=<?php echo e(url('/settings/updates')); ?> id="settings-menu-other-updates"
                                class="js-ajax-ux-request js-submenu-ajax js-dynamic-settings-url"><?php echo e(cleanLang(__('lang.updates'))); ?></a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside><?php /**PATH /www/wwwroot/orion.braincave.work/application/resources/views/pages/settings/leftmenu-settings.blade.php ENDPATH**/ ?>