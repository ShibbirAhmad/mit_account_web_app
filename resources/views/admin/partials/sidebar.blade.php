<?php
$admin = null;
if (session()->has('admin')) {
    $admin = session()->get('admin');
}
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel" style="border-bottom:1px solid #ddd;">

            <div class="pull-left image">
                @if ($admin != null && !empty($admin->image))
                    <img src="{{ asset('storage/' . $admin->image) }}" class="img-circle" alt="User Image">
                @else
                    <img src="{{ asset('admin/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info" style="top:1px;">
                @if ($admin != null)
                    <h4>{{ explode(' ', trim($admin->name))[0] }}</h4>
                @endif
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>

        </div>
        <br />

        <ul class="sidebar-menu" data-widget="tree">

            <li class="treeview">
                <a to="/"><i class="fa fa-dashboard"></i> <span>
                        <router-link :to="{ name: 'dashboard' }">Dashboard</router-link>
                    </span> </a>
            </li>

                  <li class="treeview">
                <a to="/"><i class="fa fa-money"></i> <span>
                        <router-link :to="{ name: 'mit_credit' }"> Credit
                        </router-link>
                    </span> </a>
            </li>


            <li class="treeview">
                <a to="/"><i class="fa fa-money"></i> <span>
                        <router-link :to="{ name: 'mit_debit' }"> Debit </router-link>
                    </span> </a>
            </li>


          <li class="treeview">
                <a to="/"><i class="fa fa-list-alt"></i> <span>
                        <router-link :to="{ name: 'balance' }">Mange Balance
                </router-link>
                    </span> </a>
            </li>



            <li class="treeview">
                <a to="/"><i class="fa fa-desktop"></i> <span>
                        <router-link :to="{ name: 'services' }"> Services
                        </router-link>
                    </span> </a>
            </li>


            <li class="treeview">
                <a to="/"><i class="fa fa-users"></i> <span>
                        <router-link :to="{ name: 'service_clients' }"> Clients
                        </router-link>
                    </span> </a>
            </li>





            <li class="treeview">
                <a to="/"><i class="fa fa-exchange"></i> <span>
                        <router-link :to="{ name: 'mit_fund_transfer' }">Fund Transfer
                        </router-link>

                    </span> </a>
            </li>



            <li class="treeview">
                <a to="/"><i class="fa fa-users"></i> <span>

                        <router-link :to="{ name: 'team_member' }">Manage Employee
                        </router-link>

                    </span> </a>
            </li>


            <li class="treeview">
                <a to="/"><i class="fa fa-user-secret"></i> <span>
                        <router-link :to="{ name: 'admin' }">Admins</router-link>
                    </span> </a>
            </li>


            <li class="treeview">
                <a to="/"><i class="fa fa-user-secret"></i> <span>
                        <router-link :to="{ name: 'directors' }">Directors</router-link>
                    </span> </a>
            </li>



        </ul>
    </section>
</aside>
