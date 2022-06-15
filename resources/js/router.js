import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [

    {
        path: '/',
        component: () =>
            import( /* webpackChunkName: "account_login" */ './components/admin/AccountLogin.vue'),
        name: 'account_login',
        meta: {
            title: 'Login || Panel'
        }

    },

     {
         path: '/backend/dashboard',
         component: () =>
             import( /* webpackChunkName: "admin_dashboard" */ './components/admin/dashboard/Index.vue'),
         name: 'dashboard',
         meta: {
             requiresAuthAdmin: true,
             title: 'Dashboard'
         }
     },



    {
        path: '/backend/slider',
        component: () =>
            import( /* webpackChunkName: "slider" */ './components/admin/slider/Slider'),
        name: 'slider',
        meta: {
            requiresAuthAdmin: true,
            title: 'slider'
        }
    },
    {
        path: '/backend/sliderAdd',
        component: () =>
            import( /* webpackChunkName: "slider_add" */ './components/admin/slider/Add'),
        name: "sliderAdd",
        meta: {
            requiresAuthAdmin: true,
            title: 'slider || add'
        }
    },
    {
        path: '/backend/sliderEdit/:id',
        component: () =>
            import( /* webpackChunkName: "slider_edit" */ './components/admin/slider/Edit'),
        name: 'sliderEdit',
        meta: {
            requiresAuthAdmin: true,
            title: 'slider || edit'
        }
    },


    {
        path: '/backend/category/slider',
        component: () =>
            import( /* webpackChunkName: "category_slider" */ './components/admin/Category_slider/Index'),
        name: 'category_slider',
        meta: {
            requiresAuthAdmin: true,
            title: 'category slider'
        }
    },
    {
        path: '/backend/category/slider/add',
        component: () =>
            import( /*webpackChunkName: "category_slider_add" */ './components/admin/Category_slider/Add'),
        name: "category_slider_add",
        meta: {
            requiresAuthAdmin: true,
            title: 'category slider || add'
        }
    },
    {
        path: '/backend/category/slider/edit/:id',
        component: () =>
            import( /* webpackChunkName: "category_slider_edit" */ './components/admin/Category_slider/Edit'),
        name: 'category_slider_edit',
        meta: {

            requiresAuthAdmin: true,
            title: 'category slider || edit'
        }
    },

    {
        path: '/backend/user/list',
        component: () =>
            import( /* webpackChunkName: "user"*/ './components/admin/user/index'),
        name: 'user',
        meta: {
            title: 'User | List',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/customer/list',
        component: () =>
            import( /* webpackChunkName: "customer"*/ './components/admin/customer/index'),
        name: 'customer',
        meta: {

            title: 'customer | list',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/variant',
        component: () =>
            import( /* webpackChunkName: "variant"*/ './components/admin/variant/Variant'),
        name: "variant",
        meta: {
            requiresAuthAdmin: true,
            title: 'variant'
        }
    },

    {
        path: '/backend/adminLogin',
        component: () =>
            import( /* webpackChunkName: "admin_login" */ './components/admin/Login.vue'),
        name: 'adminLogin',
        meta: {
            title: 'Login || Admin'
        }

    },

    {
        path: '/backend/supplier',
        component: () =>
            import( /* webpackChunkName: "supplier" */ './components/admin/supplier/Supplier'),
        name: 'supplier',
        meta: {
            requiresAuthAdmin: true,
            title: 'suppler'
        }

    },
    {
        path: '/backend/fabrics/add/supplier/',
        component: () =>
            import( /* webpackChunkName: "addFabricsSupplier" */ './components/admin/supplier/AddFabricsSupplier'),
        name: 'AddFabricsSupplier',
        meta: {
            requiresAuthAdmin: true,
            title: 'Fabrics| Supplier'
        }

    },
    {
        path: '/backend/fabrics/supplier',
        component: () =>
            import( /* webpackChunkName: "fabricsSupplier" */ './components/admin/supplier/FabricsSupplier'),
        name: 'FabricsSupplier',
        meta: {
            requiresAuthAdmin: true,
            title: 'Fabrics| Supplier'
        }

    },


    {
        path: '/backend/supplier/:id/amount',
        component: () =>
            import( /* webpackChunkName: "SupplierAmount" */ './components/admin/supplier/Amount'),
        name: 'SupplierAmount',
        meta: {
            requiresAuthAdmin: true,
            title: 'suppler'
        }

    },

    {
        path: '/backend/supplier/purchase/from/us/:id',
        component: () =>
            import( /* webpackChunkName: "supplier_purchase_from_us" */ './components/admin/supplier/PurchaseFromUs.vue'),
        name: 'supplier_purchase_from_us',
        meta: {
            requiresAuthAdmin: true,
            title: 'supplier reverse purchase'
        }

    },
    {
        path: '/backend/supplier/purchase/add/:id',
        component: () =>
            import( /* webpackChunkName: "supplier_purchase_add" */ './components/admin/supplier/PurchaseAdd.vue'),
        name: 'supplier_purchase_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'supplier add purchase'
        }

    },

    {
        path: '/backend/Supplier/Add',
        component: () =>
            import( /* webpackChunkName: "supplierAdd" */ './components/admin/supplier/Add'),
        name: 'supllierAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add|suppleir'
        }
    },
    {

        path: '/backend/edit/supplier/:id',
        component: () =>
            import( /* webpackChunkName: "supplierEdit" */ './components/admin/supplier/Edit'),
        name: 'supplierEdit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Edit| Supplier'
        }
    },

    {
        path: '/backend/credit',
        component: () =>
            import( /* webpackChunkName: "credit" */ './components/admin/credit/Credit'),
        name: 'credit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Credit'
        }
    },

   {
        path: '/backend/boost/service/credit',
        component: () =>
            import( /* webpackChunkName: "boost_credit" */ './components/admin/credit/BoostCredit.vue'),
        name: 'boost_credit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Boost service credit'
        }
    },

    {
        path: '/backend/tourism/credit',
        component: () =>
            import( /* webpackChunkName: "tourism_credit" */ './components/admin/credit/TourismCredit.vue'),
        name: 'tourism_credit',
        meta: {
            requiresAuthAdmin: true,
            title: 'tourism  credit'
        }
    },



    {
        path: '/backend/mit/credit',
        component: () =>
            import( /* webpackChunkName: "mit_credit" */ './components/admin/credit/MitCredit.vue'),
        name: 'mit_credit',
        meta: {
            requiresAuthAdmin: true,
            title: 'mit  credit'
        }
    },

    {
        path: '/backend/accounts/monthly/report',
        component: () =>
            import( /* webpackChunkName: "account_report" */ './components/admin/credit/Report.vue'),
        name: 'account_report',
        meta: {
            requiresAuthAdmin: true,
            title: 'account_report'
        }

    },
    {
        path: '/backend/credit/due',
        component: () =>
            import( /* webpackChunkName: "creditDue" */ './components/admin/credit/Due'),
        name: 'CreditDue',
        meta: {
            requiresAuthAdmin: true,
            title: 'Due| Credit'
        }
    },

    {
        path: '/backend/credit/add',
        component: () => import( /* webpackChunkName: "creditAdd" */ './components/admin/credit/Add.vue'),
        name: 'creditAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add credit'
        }
    },

    {
        path: '/backend/boost/credit/add',
        component: () => import( /* webpackChunkName: "boost_credit_add" */ './components/admin/credit/BoostCreditAdd.vue'),
        name: 'boost_credit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add boost credit'
        }
    },

    {
        path: '/backend/mit/credit/add',
        component: () => import( /* webpackChunkName: "mit_credit_add" */ './components/admin/credit/MitCreditAdd.vue'),
        name: 'mit_credit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add mit credit'
        }
    },

    {
        path: '/backend/tourism/credit/add',
        component: () => import( /* webpackChunkName: "tourism_credit_add" */ './components/admin/credit/TourismCreditAdd.vue'),
        name: 'tourism_credit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add tourism credit'
        }
    },


    {
        path: '/backend/credit/edit/:id',
        component: () => import( /* webpackChunkName: "creditEdit" */ './components/admin/credit/Edit'),
        name: 'creditEdit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Edit credit'
        }

    },

    {
        path: '/backend/debit',
        component: () =>
            import( /* webpackChunkName: "debit" */ './components/admin/debit/Debit'),
        name: 'debit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Debit'
        }
    },


    {
        path: '/backend/boost/service/debit',
        component: () =>
            import( /* webpackChunkName: "boost_debit" */ './components/admin/debit/BoostDebit.vue'),
        name: 'boost_debit',
        meta: {
            requiresAuthAdmin: true,
            title: 'boost service debit'
        }
    },



    {
        path: '/backend/mit/debit',
        component: () =>
            import( /* webpackChunkName: "mit_debit" */ './components/admin/debit/MitDebit.vue'),
        name: 'mit_debit',
        meta: {
            requiresAuthAdmin: true,
            title: 'mit service debit'
        }
    },



    {
        path: '/backend/tourism/debit',
        component: () =>
            import( /* webpackChunkName: "tourism_debit" */ './components/admin/debit/TourismDebit.vue'),
        name: 'tourism_debit',
        meta: {
            requiresAuthAdmin: true,
            title: 'tourism service debit'
        }
    },

    {
        path: '/backend/properties/debit',
        component: () =>
            import( /* webpackChunkName: "properties_debit" */ './components/admin/debit/PropertiesDebit.vue'),
        name: 'properties_debit',
        meta: {
            requiresAuthAdmin: true,
            title: 'properties service debit'
        }
    },


    {
        path: '/backend/debit/add',
        component: () => import( /* webpackChunkName: "debitAdd" */ './components/admin/debit/Add.vue'),
        name: 'debitAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add|debit'
        }
    },

    {
        path: '/backend/boost/debit/add',
        component: () => import( /* webpackChunkName: "boost_debit_add" */ './components/admin/debit/BoostDebitAdd.vue'),
        name: 'boost_debit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add boost debit'
        }
    },


    {
        path: '/backend/mit/debit/add',
        component: () => import( /* webpackChunkName: "mit_debit_add" */ './components/admin/debit/MitDebitAdd.vue'),
        name: 'mit_debit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add mit debit'
        }
    },

    {
        path: '/backend/tourism/debit/add',
        component: () => import( /* webpackChunkName: "tourism_debit_add" */ './components/admin/debit/TourismDebitAdd.vue'),
        name: 'tourism_debit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add tourism debit'
        }
    },

    {
        path: '/backend/properties/debit/add',
        component: () => import( /* webpackChunkName: "properties_debit_add" */ './components/admin/debit/PropertiesDebitAdd.vue'),
        name: 'properties_debit_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'add properties debit'
        }
    },

    {
        path: '/backend/debit/edit/:id',
        component: () =>
            import( /* webpackChunkName: "debitEdit" */ './components/admin/debit/Edit'),
        name: 'debitEdit',
        meta: {
            requiresAuthAdmin: true,
            title: 'Edit|Debit'
        }

    },


    {
        path: '/backend/city',
        component: () =>
            import( /* webpackChunkName: "city" */ './components/admin/city/City'),
        name: 'city',
        meta: {
            requiresAuthAdmin: true,
            title: 'city'
        }
    },

    {
        path: '/backend/sub/city',
        component: () =>
            import( /* webpackChunkName: "sub_city" */ './components/admin/sub_city/Index.vue'),
        name: 'sub_city',
        meta: {
            requiresAuthAdmin: true,
            title: 'sub city'
        }
    },

    {
        path: '/backend/add/sub/city',
        component: () =>
            import( /* webpackChunkName: "sub_city_add" */ './components/admin/sub_city/Add.vue'),
        name: 'add_sub_city',
        meta: {
            requiresAuthAdmin: true,
            title: 'add sub city'
        }
    },
    {
        path: '/backend/edit/sub/city/:id',
        component: () =>
            import( /* webpackChunkName: "edit_sub_city" */ './components/admin/sub_city/Edit.vue'),
        name: 'edit_sub_city',
        meta: {
            requiresAuthAdmin: true,
            title: 'edit sub city'
        }
    },
    {
        path: '/backend/role',
        component: () =>
            import( /* webpackChunkName: "role" */ './components/admin/role/Role'),
        name: 'role',
        meta: {
            requiresAuthAdmin: true,
            title: 'Role'
        }

    },

    {
        path: '/backend/role/add',
        component: () =>
            import( /* webpackChunkName: "roleAdd" */ './components/admin/role/Add'),
        name: 'roleAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'Add ROle'
        }

    },

    {
        path: '/backend/permissions/edit/role/:id',
        component: () =>
            import( /* webpackChunkName: "editPermission" */ './components/admin/role/Permissions'),
        name: 'EditPermissions',
        meta: {
            requiresAuthAdmin: true,
            title: 'Edit| Permissions'
        }

    },

    {
        path: '/backend/addCity',
        component: () =>
            import( /* webpackChunkName: "cityAdd" */ './components/admin/city/Add'),
        name: 'cityAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add city'
        }
    },
    {
        path: '/backend/editCity/:id',
        component: () =>
            import( /* webpackChunkName: "cityEdit" */ './components/admin/city/Edit'),
        name: 'editCity',
        meta: {
            requiresAuthAdmin: true,
            title: 'edit city'
        }
    },

    {
        path: '/backend/company',
        component: () =>
            import( /* webpackChunkName: "company" */ './components/admin/company/Company'),
        name: 'company',
        meta: {
            requiresAuthAdmin: true,
            title: 'company'
        }

    },
    {
        path: '/backend/add/company',
        component: () =>
            import( /* webpackChunkName: "addCompany" */ './components/admin/company/Add'),
        name: 'addCompany',
        meta: {
            requiresAuthAdmin: true,
            title: 'add company'
        }
    },
    {
        path: '/backend/edit/company/:id',
        component: () =>
            import( /* webpackChunkName: "editCompany" */ './components/admin/company/Edit'),
        name: 'editCompany',
        meta: {
            requiresAuthAdmin: true,
            title: 'edit company'
        }
    },


    {
        path: '/backend/mit/fund/transfer',
        component: () => import( /* webpackChunkName: "mit_fund_transfer" */ './components/admin/account/MitBalanceTransfer.vue'),
        name: 'mit_fund_transfer',
        meta: {
            requiresAuthAdmin: true,
            title: 'mit fund transfer'
        }
    },

    {
        path: '/backend/mit/fund/transfer/add',
        component: () =>
            import( /* webpackChunkName: "mit_fund_transfer_add" */ './components/admin/account/MitBalanceTransferAdd.vue'),
        name: 'mit_fund_transfer_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'mit fund transfer add'
        }

    },


    {
        path: '/backend/boost/fund/transfer',
        component: () => import( /* webpackChunkName: "boost_fund_transfer" */ './components/admin/account/BoostBalanceTransfer.vue'),
        name: 'boost_fund_transfer',
        meta: {
            requiresAuthAdmin: true,
            title: 'boost fund transfer'
        }
    },

    {
        path: '/backend/boost/fund/transfer/add',
        component: () =>
            import( /* webpackChunkName: "boost_fund_transfer_add" */ './components/admin/account/BoostBalanceTransferAdd.vue'),
        name: 'boost_fund_transfer_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'boost fund transfer add'
        }
    },


    {
        path: '/backend/tourism/fund/transfer',
        component: () => import( /* webpackChunkName: "tourism_fund_transfer" */ './components/admin/account/TourismBalanceTransfer.vue'),
        name: 'tourism_fund_transfer',
        meta: {
            requiresAuthAdmin: true,
            title: 'tourism fund transfer'
        }
    },

    {
        path: '/backend/tourism/fund/transfer/add',
        component: () =>
            import( /* webpackChunkName:"tourism_fund_transfer_add" */ './components/admin/account/TourismBalanceTransferAdd.vue'),
        name: 'tourism_fund_transfer_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'tourism fund transfer add'
        }
    },



    {
        path: '/backend/properties/fund/transfer',
        component: () => import( /* webpackChunkName: "properties_fund_transfer" */ './components/admin/account/PropertiesBalanceTransfer.vue'),
        name: 'properties_fund_transfer',
        meta: {
            requiresAuthAdmin: true,
            title: 'properties fund transfer'
        }
    },

    {
        path: '/backend/properties/fund/transfer/add',
        component: () =>
            import( /* webpackChunkName:"properties_fund_transfer_add" */ './components/admin/account/PropertiesBalanceTransferAdd.vue'),
        name: 'properties_fund_transfer_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'properties fund transfer add'
        }
    },

    {
        path: '/backend/department/loan',
        component: () =>
            import( /* webpackChunkName:"department_loan" */ './components/admin/department_loan/Index.vue'),
        name: 'department_loan',
        meta: {
            requiresAuthAdmin: true,
            title: 'department loan '
        }
    },
    {
        path: '/backend/department/loan/take',
        component: () =>
            import( /* webpackChunkName:"department_loan_add" */ './components/admin/department_loan/Add.vue'),
        name: 'department_loan_add',
        meta: {
            requiresAuthAdmin: true,
            title: 'department loan '
        }
    },

    {
        path: '/backend/department/loan/transaction/details/:id',
        component: () =>
            import( /* webpackChunkName:"department_loan_transactions" */ './components/admin/department_loan/Transactions.vue'),
        name: 'department_loan_transactions',
        meta: {
            requiresAuthAdmin: true,
            title: 'department loan transactions '
        }
    },

    {
        path: '/backend/offer',
        component: () =>
            import( /* webpackChunkName: "offer" */ './components/admin/offer/Offer'),
        name: 'offer',
        meta: {
            requiresAuthAdmin: true,
            title: 'offer'
        }

    },
    {
        path: '/backend/add',
        component: () =>
            import( /* webpackChunkName: "offerAdd" */ './components/admin/offer/Add'),
        name: 'offerAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add offer'
        }
    },
    {
        path: '/backend/edit/Offer/:id',
        component: () =>
            import( /* webpackChunkName: "edit_offer" */ './components/admin/offer/Edit'),
        name: 'edit_offer',
        meta: {
            requiresAuthAdmin: true,
            title: 'edit offer'
        }
    },

    {
        path: '/backend/courier',
        component: () =>
            import( /* webpackChunkName: "courier" */ './components/admin/courier/Courier'),
        name: 'courier',
        meta: {
            requiresAuthAdmin: true,
            title: 'courier'
        }

    },
    {
        path: '/backend/addCourier',
        component: () =>
            import( /* webpackChunkName: "courierAdd" */ './components/admin/courier/Add'),
        name: 'courierAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'add courier'
        }
    },
    {
        path: '/backend/editCourier/:id',
        component: () =>
            import( /* webpackChunkName: "editCourier" */ './components/admin/courier/Edit'),
        name: 'editCourier',
        meta: {
            requiresAuthAdmin: true,
            title: 'Courier edit'
        }
    },

    {
        path: '/backend/comment',
        component: () =>
            import( /* webpackChunkName: "comment" */ './components/admin/comment/Comment'),
        name: 'comment',
        meta: {
            requiresAuthAdmin: true,
            title: 'comment'
        }

    },
    {
        path: '/backend/addComment',
        component: () =>
            import( /* webpackChunkName: "commentAdd" */ './components/admin/comment/Add'),
        name: 'commentAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'comment || add'
        }
    },
    {
        path: '/backend/editComment/:id',
        component: () =>
            import( /* webpackChunkName: "editComment" */ './components/admin/comment/Edit'),
        name: 'editComment',
        meta: {
            requiresAuthAdmin: true,
            title: 'comment edit'
        }
    },

    {
        path: '/backend/factory/list',
        component: () =>
            import( /* webpackChunkName: "factory_list" */ './components/admin/factory/Index'),
        name: 'factory_list',
        meta: {
            requiresAuthAdmin: true,
            title: 'factory'
        }

    },
    {
        path: '/backend/factory/add',
        component: () =>
            import( /* webpackChunkName: "add_factory" */ './components/admin/factory/Add'),
        name: 'add_factory',
        meta: {
            requiresAuthAdmin: true,
            title: 'factory || add'
        }
    },
    {
        path: '/backend/edit/factory/:id',
        component: () =>
            import( /* webpackChunkName: "edit_factory" */ './components/admin/factory/Edit'),
        name: 'edit_factory',
        meta: {
            requiresAuthAdmin: true,
            title: 'factroy edit'
        }
    },

    {
        path: '/backend/product/:number_of_page',
        component: () =>
            import( /* webpackChunkName: "product" */ './components/admin/product/product.vue'),
        name: 'product',
        meta: {
            requiresAuthAdmin: true,
            title: 'all Products'
        }

    },


    {
        path: '/backend/report/product/stock',
        component: () =>
            import( /* webpackChunkName: "productStock" */ './components/admin/Report/ProductStock'),
        name: 'productStcok',
        meta: {
            requiresAuthAdmin: true,
            title: 'Product||stock'
        }

    },


    {
        path: '/backend/addProduct',
        component: () =>
            import( /* webpackChunkName: "productAdd" */ './components/admin/product/Add'),
        name: 'productAdd',
        meta: {
            requiresAuthAdmin: true,
            title: 'product || add'
        }
    },
    {
        path: '/backend/product/edit/:id',
        component: () =>
            import( /* webpackChunkName: "productEdit" */ './components/admin/product/Edit'),
        name: 'productEdit',
        meta: {
            requiresAuthAdmin: true,
            title: 'product edit'
        }
    },


    {
        path: '/backend/carrier/add',
        component: () =>
            import( /* webpackChunkName: "add_carrier" */ './components/admin/carrier/Add'),
        name: 'add_carrier',
        meta: {
            title: 'Carrier | Add',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/carrier/show/:id',
        component: () =>
            import( /* webpackChunkName: "show_carrier" */ './components/admin/carrier/Show'),
        name: 'show_carrier',
        meta: {
            title: 'Carrier | show',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/carrier/edit/:id',
        component: () =>
            import( /* webpackChunkName: "edit_carrier" */ './components/admin/carrier/Edit'),
        name: 'edit_carrier',
        meta: {
            title: 'Carrier | Home',
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/job/post/applied/users/:id',
        component: () =>
            import( /* webpackChunkName: "applied_user" */ './components/admin/carrier/jobApplied'),
        name: 'applied_users',
        meta: {
            title: 'Carrier | Applied User',
            requiresAuthAdmin: true,
        }
    },



    {
        path: '/backend/account/purpose',
        component: () =>
            import( /* webpackChunkName: "account_purpose" */ './components/admin/account/accountPurpose'),
        name: 'account_purpose',
        meta: {
            title: 'Account | Purpose',
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/account/purpose/add',
        component: () =>
            import( /* webpackChunkName: "account_purpose_add" */ './components/admin/account/AccountPurposeAdd.vue'),
        name: 'account_purpose_add',
        meta: {
            title: 'Account | Purpose Add',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/account/purpose/edit/:id',
        component: () =>
            import( /* webpackChunkName: "accoun_purpose_edit" */ './components/admin/account/AccountPurposeEdit.vue'),
        name: 'account_purpose_edit',
        meta: {
            title: 'Account | Purpose edit',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/team/member',
        component: () =>
            import( /* webpackChunkName: "team_member" */ './components/admin/team/index'),
        name: 'team_member',
        meta: {
            title: 'Team | Members',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/team/member/add',
        component: () =>
            import( /* webpackChunkName: "add_team_member" */ './components/admin/team/Add'),
        name: 'add_team_member',
        meta: {

            title: 'Add | Member',
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/team/member/edit/:id',
        component: () =>
            import( /* webpackChunkName: "edit_team_member" */ './components/admin/team/Edit'),
        name: 'edit_team_member',
        meta: {
            title: 'Edit | Member',
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/team/salary/:id',
        component: () =>
            import( /* webpackChunkName: "teamsalary" */ './components/admin/team/Salary'),
        name: 'teamSalary',
        meta: {
            title: 'Team| Salary',
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/account/report',
        component: () =>
            import( /* webpackChunkName: "AccountReport" */ './components/admin/Report/Account'),
        name: 'AccountReport',
        meta: {
            requiresAuthAdmin: true,
            title: 'Office|sale | Report'
        }
    },
    {
        path: '/backend/product/stock/report',
        component: () => import( /* webpackChunkName: "product_stock_tracking" */ './components/admin/Report/productStockTracking'),
        name: 'product_stock_tracking',
        meta: {
            requiresAuthAdmin: true,
            title: 'product stock tracking'
        }
    },

    {
        path: '/backend/listAdmin',
        component: () =>
            import( /* webpackChunkName: "admin" */ './components/admin/admin/Admin'),
        name: 'admin',
        meta: {
            requiresAuthAdmin: true,
            title:'admins '
        }

    },


    {
        path: '/backend/admin/profile',
        component: () =>
            import( /*webpackChunkName: "adminProfile" */ './components/admin/admin/Profile'),
        name: 'adminProfile',
        meta: {
            requiresAuthAdmin: true,
            title: 'Profile | Admin'
        }

    },
    {
        path: '/backend/addAdmin',
        component: () =>
            import( /* webpackChunkName: "adminAdd" */ './components/admin/admin/Add'),
        name: 'adminAdd',
        meta: {
            requiresAuthAdmin: true,
            title: ' Add Admin '
        }
    },
    {
        path: '/backend/editAdmin/:adminId',
        component: () =>
            import( /* webpackChunkName: "editAdmin" */ './components/admin/admin/Edit'),
        name: 'editAdmin',
        meta: {
            requiresAuthAdmin: true,
            title: 'Edit Admin '

        }
    },
    {
        path: '/backend/assign/role/admin/:id',
        component: () =>
            import( /* webpackChunkName: "assingRoleAdmin" */ './components/admin/admin/Role'),
        name: 'AssignRoleAdmin',
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/assign/admin/permission/:id',
        component: () =>
            import( /* webpackChunkName: "assign_admin_permission" */ './components/admin/admin/AdminPermission'),
        name: 'assign_admin_permission',
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/admin/password/:id',
        component: () =>
            import( /* webpackChunkName: "passwordChange" */ './components/admin/admin/Password'),
        name: 'passwordChange',
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/admin/note/add',
        name: 'admin_note_add',
        component: () =>
            import( /*webpackChunkName: "admin_note_add"*/ './components/admin/admin/NoteAdd.vue'),
        meta: { requiresAuthAdmin: true,
                title: 'admin note add' }
    },

    {
        path: '/backend/admin/note/list',
        name: 'admin_note',
        component: () =>
            import( /*webpackChunkName: "admin_note"*/ './components/admin/admin/NoteList.vue'),
        meta: { requiresAuthAdmin: true,
                title: 'admin note ' }
    },


    {
        path: '/backend/sms/campaign',
        component: () =>
            import( /* webpackChunkName: "sms_campaign" */ './components/admin/sms/Send'),
        name: 'sms_campaign',
        meta: {
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/subscriber/list',
        component: () =>
            import( /* webpackChunkName: "subscriber_list" */ './components/admin/subscriber/Index'),
        name: 'subscriber_list',
        meta: {
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/contact/message',
        component: () =>
            import( /* webpackChunkName: "contact_messgae" */ './components/admin/contact/Index'),
        name: 'contact_message',
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/contact/message/reply/:id',
        component: () =>
            import( /* webpackChunkName: "contact_messgae_reply" */ './components/admin/contact/Reply'),
        name: 'contact_message_reply',
        meta: {
            requiresAuthAdmin: true
        }
    },


    {
        path: '/backend/loan/add',
        component: () =>
            import( /* webpackChunkName: "addLoan" */ './components/admin/loan/Add'),
        name: 'loanAdd',
        meta: {
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/loan/manage',
        component: () =>
            import( /* webpackChunkName: "Loan" */ './components/admin/loan/Loan'),
        name: 'loan',
        meta: {

            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/loaner/detaials/:id',
        component: () =>
            import( /* webpackChunkName: "LoanDetails" */ './components/admin/loan/LoanerDetails'),
        name: 'LoanerDetails',
        meta: {
            requiresAuthAdmin: true
        }
    },






    {
        path: '/backend/general/setting',
        component: () =>
            import( /* webpackChunkName: "generl setting" */ './components/admin/general_setting/General.vue'),
        name: 'general_setting',
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/flash/deals',
        name: 'flash_deals',
        component: () =>
            import( /* webpackChunkName: "flash_deals" */ './components/admin/flash_deals/FlashDeals.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/seasional/campaign/menage',
        name: 'seasonal_campaign',
        component: () =>
            import( /* webpackChunkName: "seasonal campaign" */ './components/admin/seasonal/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },


    {
        path: '/backend/occasional/campaign/menage',
        name: 'occasional_campaign',
        component: () =>
            import( /* webpackChunkName: "occasional_campaign" */ './components/admin/occasional/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },


    {
        path: '/backend/buy/one/get/one/campaign',
        name: 'buy_one_get_one',
        component: () =>
            import( /* webpackChunkName: "buy_one_get_one" */ './components/admin/buy_one_get_one/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/display/user/review/of/products',
        name: 'customer_review',
        component: () =>
            import( /* webpackChunkName: "customer_review" */ './components/admin/product_review/Review.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/company/assets',
        name: 'company_assets',
        component: () =>
            import( /* webpackChunkName: "company_assets" */ './components/admin/company_assets/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/company/assets/add',
        name: 'company_assets_add',
        component: () =>
            import( /* webpackChunkName: "company_assets_add" */ './components/admin/company_assets/Add.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/company/assets/edit/:id',
        name: 'company_assets_edit',
        component: () =>
            import( /* webpackChunkName: "company_assets_edit" */ './components/admin/company_assets/Edit.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },



    {
        path: '/backend/company/sale/details/:id',
        component: () => import(/* webpackChunkName: "company_sale_details" */'./components/admin/sale/CompanySaleDetails.vue'),
        name: 'company_sale_details',
        meta: {
            requiresAuthAdmin: true,
            title: 'Company  | Sale Details'
        }
    },

    {
        path: '/backend/company/payment/details/:id',
        component: () => import(/* webpackChunkName: "company_payment_details" */'./components/admin/sale/CompanyPaymentDetails.vue'),
        name: 'company_payment_details',
        meta: {
            requiresAuthAdmin: true,
            title: 'Company  | Payment  Details'
        }
    },

    {
        path: '/backend/company/investment',
        name: 'investment',
        component: () =>
            import( /* webpackChunkName: "investment" */ './components/admin/Investment/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/company/investment/add',
        name: 'investment_add',
        component: () =>
            import( /* webpackChunkName: "investment_add" */ './components/admin/Investment/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },
    {
        path: '/backend/company/investment/edit/:id',
        name: 'investment_edit',
        component: () =>
            import( /* webpackChunkName: "investment_edit" */ './components/admin/Investment/Edit.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/company/investor/details/:id',
        name: 'investor_details',
        component: () =>
            import( /* webpackChunkName: "investor_details" */ './components/admin/Investment/InvestorDetails.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/print/house',
        name: 'print_house',
        component: () =>
            import( /*webpackChunkName: "print_house" */ './components/admin/print_house/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/print/house/add',
        name: 'print_house_add',
        component: () =>
            import( /*webpackChunkName: "print_house_add" */ './components/admin/print_house/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/print/house/details/:id',
        name: 'print_house_details',
        component: () =>
            import( /*webpackChunkName:"print_house_details"*/ './components/admin/print_house/PrintHouseDetails.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/add/product/for/print/:id',
        name: 'print_product_add',
        component: () =>
            import( /*webpackChunkName: "print_product_add" */ './components/admin/print_house/AddPrintProduct.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/receieve/printed/product/:id',
        name: 'receieve_printed_product',
        component: () =>
            import( /*webpackChunkName: "receieve_printed_product" */ './components/admin/print_house/ReceievePrinted.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/details/print/product/:name',
        name: 'print_details',
        component: () =>
            import( /*webpackChunkName: "print_details" */ './components/admin/print_house/PrintDetails.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },

    {
        path: '/backend/details/receive/print/product/:name',
        name: 'receive_print_details',
        component: () =>
            import( /*webpackChunkName: "receive_print_details" */ './components/admin/print_house/ReceivePrintDetails.vue'),
        meta: {
            requiresAuthAdmin: true,
        }
    },


    {
        path: '/backend/prop/up/banner/campaign',
        name: 'prop_up_banner',
        component: () =>
            import( /* webpackChunkName: "prop_up_banner" */ './components/admin/prop_up_banner/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },


    {
        path: '/backend/bill/statement',
        name: 'bill_statement',
        component: () =>
            import( /*webpackChunkName: "bill_statement" */ './components/admin/bill_statement/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
            title:'bill statements '
        }
    },

    {
        path: '/backend/bill/statement/add',
        name: 'bill_statement_add',
        component: () =>
            import( /*webpackChunkName: "bill_statement_add" */ './components/admin/bill_statement/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
            title:'bill statement add '
        }
    },

    {
        path: '/backend/bill/statement/details/:id',
        name: 'bill_statement_details',
        component: () =>
            import( /*webpackChunkName:"bill_statement_details"*/ './components/admin/bill_statement/BillDetails.vue'),
        meta: {
            requiresAuthAdmin: true,
            title:'bill statement details '
        }
    },




    {
        path: '/backend/note/add/:id',
        name: 'supplier_note_add',
        component: () =>
            import( /*webpackChunkName: "supplier_note_add"*/ './components/admin/Supplier/NoteAdd.vue'),
        meta: { requiresAuthAdmin: true }
    },

    {
        path: '/backend/note/list/:id',
        name: 'supplier_note',
        component: () =>
            import( /*webpackChunkName: "supplier_note"*/ './components/admin/Supplier/NoteList.vue'),
        meta: { requiresAuthAdmin: true }
    },


    {
        path: '/backend/coupon/code',
        name: 'coupon',
        component: () =>
            import( /* webpackChunkName: "coupon" */ './components/admin/coupon/Index.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },

    {
        path: '/backend/coupon/code/add',
        name: 'coupon_add',
        component: () =>
            import( /* webpackChunkName: "coupon_add" */ './components/admin/coupon/Add.vue'),
        meta: {
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/coupon/code/edit/:id',
        name: 'coupon_edit',
        component: () =>
            import( /* webpackChunkName: "coupon_edit" */ './components/admin/coupon/Edit.vue'),
        meta: { requiresAuthAdmin: true, }
    },

    {
        path: '/backend/product/transfer/add',
        name: 'product_transfer_add',
        component: () => import(/*webpackChunkName:"product_transfer_add"*/'./components/admin/product/transfer/add.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'Product Transfer Add'
        }
    },
    {
        path: '/backend/product/transfer/',
        name: 'product_transfer',
        component: () => import(/*webpackChunkName:"product_transfer"*/'./components/admin/product/transfer/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'Product Transfer'
        }
    },


    {
        path: '/backend/services',
        name: 'services',
        component: () => import(/*webpackChunkName: "services" */'./components/admin/mohasagor_it/Service/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'services '
        }
    },

    {
        path: '/backend/service/add',
        name: 'service_add',
        component: () => import(/*webpackChunkName: "service_add" */'./components/admin/mohasagor_it/Service/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'add service '
        }
    },

    {
        path: '/backend/service/edit/:id',
        name: 'service_edit',
        component: () => import(/*webpackChunkName: "service_edit" */'./components/admin/mohasagor_it/Service/Edit.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'edit service '
        }
    },


    {
        path: '/backend/services/service/details/:id',
        name: 'service_details',
        component: () => import(/*webpackChunkName: "service_details" */'./components/admin/mohasagor_it/Service/Details.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: ' service details '
        }
    },



    {
        path: '/backend/service/client/add/',
        name: 'service_client_add',
        component: () => import(/*webpackChunkName: "service_client_add" */'./components/admin/mohasagor_it/client/ClientAdd.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'add client '
        }
    },

    {
        path: '/backend/service/client/edit/:id',
        name: 'service_client_edit',
        component: () => import(/*webpackChunkName: "service_client_edit" */'./components/admin/mohasagor_it/client/ClientEdit.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'edit  client'
        }
    },

    {
        path: '/backend/service/clients',
        name: 'service_clients',
        component: () => import(/*webpackChunkName:"service_clients"*/'./components/admin/mohasagor_it/client/Clients.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'clients'
        }
    },


    {
        path: '/backend/service/package/add/',
        name: 'service_package_add',
        component: () => import(/*webpackChunkName: "service_package_add" */'./components/admin/mohasagor_it/package/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'add service package '
        }
    },



    {
        path: '/backend/client/service/and/payment/:client_id',
        name: 'service_client_and_payment',
        component: () => import(/*webpackChunkName:"service_client_and_payment"*/'./components/admin/mohasagor_it/client/PackageAndPayments.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'client service and payments '
        }
    },


    {
        path: '/backend/mit/client/payment/details/:id',
        name: 'mit_clients_payment',
        component: () => import(/*webpackChunkName:"mit_clients_payment"*/'./components/admin/mohasagor_it/MonthlyRecord.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'mit clients payment details'
        }
    },


    {
        path: '/backend/campaign/manage',
        component: () =>
            import( /* webpackChunkName: "backendCampaign" */ './components/admin/campaign/Index'),
        name: 'backendCampaign',
        meta: {

            title: "campaign",
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/campaign/add',
        component: () => import( /* webpackChunkName: "campaignAdd" */ './components/admin/campaign/Add'),
        name: 'campaignAdd',
        meta: {
            title: "campaign|add",
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/campaign/edit/:id',
        component: () =>
            import( /* webpackChunkName: "campaignEdit" */ './components/admin/campaign/Edit'),
        name: 'campaign_edit',
        meta: {
            title: "campaign|Edit",
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/campaign/image/:id',
        component: () =>
            import( /* webpackChunkName: "campaign_image" */ './components/admin/campaign/Image'),
        name: 'campaign_image',
        meta: {
            title: "campaign|Image",
            requiresAuthAdmin: true
        }
    },
    {
        path: '/backend/campaign/slider',
        component: () =>
            import( /* webpackChunkName: "campaign_slider" */ './components/admin/campaign/Slider'),
        name: 'campaign_slider',
        meta: {
            title: "campaign|Slider",
            requiresAuthAdmin: true,

        }
    },

    {
        path: '/backend/balance',
        name: 'balance',
        component: () => import(/*webpackChunkName: "balance" */'./components/admin/balance/Index.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'balance'
        }
    },

    {
        path: '/backend/balance/add',
        name: 'balance_add',
        component: () => import(/*webpackChunkName: "balance_add" */'./components/admin/balance/Add.vue'),
        meta: {
            requiresAuthAdmin: true,
            title: 'balance add'
        }
    },

    {
        path: '/backend/parallax/banner/campaign',
        name: 'parallax_banner',
        component: () => import(/* webpackChunkName: "parallax_banner" */'./components/admin/parallax_banner/Index.vue'),
        meta: {
            title: 'parallax banner campaign',
            requiresAuthAdmin: true,
        }
    },



    {
        path: '/backend/product/bulk/print/preview',
        component: () => import(/* webpackChunkName: "bulk_product_print_preview" */'./components/admin/product/BulkPrintPreview.vue'),
        name: 'bulk_product_print_preview',
        props: {
                header: true,
                content: true
               },
        meta: {
            requiresAuthAdmin: true,
            title: 'bulk products print barcode preview'
           }
    },




]


const router = new VueRouter({
    mode: 'history',
    base: __dirname,
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuthAdmin)) {
        if (localStorage.getItem('admin_token')) {
            next()
            return
        }
        next('/backend/adminLogin')
    } else {
        next()
    }

})

export default router