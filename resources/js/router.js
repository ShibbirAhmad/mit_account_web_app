import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [

    {
        path: "/merchant/backend/login",
        component: () =>
          import(
            /* webpackChunkName: "merchant_login" */ "./components/merchant/Login"
          ),
        name: "merchant_login",
        meta: {
          title: "Merchant | Login",
        },
      },
    
      {
        path: "/merchant/password/reset",
        component: () =>
          import(
            /* webpackChunkName: "merchant_password_reset" */ "./components/merchant/PasswordReset"
          ),
        name: "merchant_password_reset",
        meta: {
          title: "Merchant Password Reset",
        },
      },
      {
        path: "/merchant/code/verify/:phone",
        component: () =>
          import(
            /* webpackChunkName: "merchant_code_verified" */ "./components/merchant/CodeVerified"
          ),
        name: "merchant_code_verified",
        meta: {
          title: "Merchant Password Reset",
        },
      },
    
      {
        path: "/merchant/reset/new/password/:phone",
        component: () =>
          import(
            /* webpackChunkName: "new_password_merchant" */ "./components/merchant/NewPassword"
          ),
        name: "new_password_merchant",
        meta: {
          title: "Update Merchant Password",
        },
      },
    
      {
        path: "/merchant/register",
        component: () =>
          import(
            /* webpackChunkName: "merchant_register" */ "./components/merchant/Register"
          ),
        name: "merchant_register",
        meta: {
          title: "Merchant | Register ",
        },
      },
    
      {
        path: "/merchant/backend/home",
        component: () =>
          import(
            /* webpackChunkName: "merchant_dashboard" */ "./components/merchant/Dashboard"
          ),
        name: "merchant_dashboard",
        meta: {
          title: "Merchant|Dashboard",
        },
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
        path: '/backend/adminLogin',
        component: () =>
            import( /* webpackChunkName: "admin_login" */ './components/admin/Login.vue'),
        name: 'adminLogin',
        meta: {
            title: 'Login || Admin'
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
            path: '/backend/boost/agency',
            name: 'boost_agency',
            component: () => import( /*webpackChunkName: "boost_agency" */ './components/admin/boost_agency/Index.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'boost agency'
            }
        },

        {
            path: '/backend/boost/agency/add',
            name: 'boost_agency_add',
            component: () => import( /*webpackChunkName: "boost_agency_add" */ './components/admin/boost_agency/Add.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'add agency'
            }
        },

        {
            path: '/backend/boost/agency/edit/:id',
            name: 'boost_agency_edit',
            component: () => import( /*webpackChunkName: "boost_agency_edit" */ './components/admin/boost_agency/Edit.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'edit agency'
            }
        },

        {
            path: '/backend/boost/agency/reseller/add',
            name: 'boost_agency_reseller_add',
            component: () => import( /*webpackChunkName: "boost_agency_reseller_add" */ './components/admin/boost_agency/BoostResellerAdd.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'add user '
            }
        },

        {
            path: '/backend/boost/agency/resellers',
            name: 'boost_agency_resellers',
            component: () => import( /*webpackChunkName:"boost_agency_resellers"*/ './components/admin/boost_agency/AgencyResellers.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'users'
            }
        },


        {
            path: '/backend/boost/resellers/dollar/and/payment/:id',
            name: 'boost_reselller_dollar_payment',
            component: () => import( /*webpackChunkName:"boost_reselller_dollar_payment"*/ './components/admin/boost_agency/ResellerDollarAndPayment.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'boost agency resellers dollars and payments '
            }
        }, {
            path: '/backend/boost/agency/reseller/info/edit/:id',
            name: 'boost_agency_reseller_edit',
            component: () => import( /*webpackChunkName: "boost_agency_reseller_edit" */ './components/admin/boost_agency/BoostResellerEdit.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'boost reseller info edit '
            }
        },


        {
            path: '/backend/boost/agency/payment/details/:id',
            name: 'boost_agency_details',
            component: () => import( /*webpackChunkName:"boost_agency_details"*/ './components/admin/boost_agency/AgencyDollarAndPayment.vue'),
            meta: {
                requiresAuthAdmin: true,
                title: 'boost agency payment details'
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
            import( /* webpackChunkName: "account_purpose_edit" */ './components/admin/account/AccountPurposeEdit.vue'),
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
            import( /* webpackChunkName: "teamSalary" */ './components/admin/team/Salary'),
        name: 'teamSalary',
        meta: {
            title: 'Team| Salary',
            requiresAuthAdmin: true,
        }
    },
    {
        path: '/backend/profit/report',
        component: () =>
            import( /* webpackChunkName: "profit_report" */ './components/admin/Report/Profit.vue'),
        name: 'profit_report',
        meta: {
            requiresAuthAdmin: true,
            title: 'Profit Report'
        }
    },


    {
        path: '/backend/account/report',
        component: () =>
            import( /* webpackChunkName: "AccountReport" */ './components/admin/Report/Account'),
        name: 'AccountReport',
        meta: {
            requiresAuthAdmin: true,
            title: ' Report'
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
            import( /* webpackChunkName: "AssignRoleAdmin" */ './components/admin/admin/Role'),
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
        path: '/backend/contact/message',
        component: () =>
            import( /* webpackChunkName: "contact_message" */ './components/admin/contact/Index'),
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
        path: '/backend/loaner/details/:id',
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
            import( /* webpackChunkName: "general_setting" */ './components/admin/general_setting/General.vue'),
        name: 'general_setting',
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
        path: '/backend/service/package/add/:client_phone?',
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
             path: '/backend/director',
             name: 'directors',
             component: () => import( /*webpackChunkName: "admin/directors" */ './components/admin/directors/Index.vue'),
             meta: {
                 requiresAuthAdmin: true,
                 title: 'director '
             }
         },

         {
             path: '/backend/director/add',
             name: 'director_add',
             component: () => import( /*webpackChunkName: "admin/director_add" */ './components/admin/directors/Add.vue'),
             meta: {
                 requiresAuthAdmin: true,
                 title: 'director add'
             }
         },

         {
             path: '/backend/director/edit/:id',
             name: 'director_edit',
             component: () => import( /*webpackChunkName: "admin/director_edit" */ './components/admin/directors/Edit.vue'),
             meta: {
                 requiresAuthAdmin: true,
                 title: 'director  edit'
             }
         },


         {
             path: '/backend/director/payment/details/:id',
             name: 'director_payment_details',
             component: () => import( /*webpackChunkName:"admin/director_payment_details"*/ './components/admin/directors/Details.vue'),
             meta: {
                 requiresAuthAdmin: true,
                 title: 'director payment history '
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
        path: "/backend/merchant",
        component: () =>
          import(
            /* webpackChunkName: "merchant" */ "./components/admin/merchant/index"
          ),
        name: "merchant",
        meta: {
          title: "Merchant ",
          requiresAuthAdmin: true,
        },
      },
      {
        path: "/backend/merchant/add",
        component: () =>
          import(
            /* webpackChunkName: "add_merchant" */ "./components/admin/merchant/Add"
          ),
        name: "add_merchant",
        meta: {
          title: "Merchant | Add ",
          requiresAuthAdmin: true,
        },
      },
      {
        path: "/backend/merchant/edit/:id",
        component: () =>
          import(
            /* webpackChunkName: "edit_merchant" */ "./components/admin/merchant/Edit"
          ),
        name: "edit_merchant",
        meta: {
          title: "Merchant | edit ",
          requiresAuthAdmin: true,
        },
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