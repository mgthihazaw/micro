<template>
  <div class="wrapper">
    <login v-if="!isLoggedIn"></login>
    <div v-if="isLoggedIn">
      <nav class="main-header navbar navbar-expand bg-success navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link pushmenu" data-widget="pushmenu">
              <i class="fa fa-bars black"></i>
            </a>
          </li>
        </ul>

        <!-- SEARCH FORM -->
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidecolor elevation-4">
        <!-- Brand Logo -->
        <router-link to="/" class="brand-link">
          <img
            :src="'/images/logo/2.png'"
            alt="Microstack Logo"
            class="brand-image elevation-3"
            style="opacity: .8"
          />
          <span class="brand-text font-weight-light text-primary">
            <br />
          </span>
        </router-link>

        <!-- Sidebar -->
        <hr />
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-2 mb-3 d-flex">
            <div class="image">
              <img :src="'/images/profile.jpg'" class="img-circle elevation-2" alt="User Image" />
            </div>

            <div class="info">
              <a class="d-block text-white">{{ username | username }}</a>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="text-white" v-if="show">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
              <!-- Add icons to the links using the .nav-icon class
              with font-awesome or any other icon font library-->
              <li class="nav-item">
                <router-link to="/" class="nav-link">
                  <i class="nav-icon green fas fa-tachometer-alt"></i>
                  <p>Dashboard</p>
                </router-link>
              </li>

              <li class="nav-item">
                <router-link class="nav-link" to="/staffs" v-if="can('staff-list')">
                  <i class="nav-icon text-primary fas fa-people-carry"></i>
                  <p>Staffs</p>
                </router-link>
              </li>

              <!-- <li class="nav-item has-treeview" v-if="can('staff-list')">
                <a class="nav-link">
                  <i class="nav-icon fas fa-file-pdf text-info"></i>
                  <p>
                    Reports
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link class="nav-link" to="/reports/services">
                      <i class="far fa-circle nav-icon text-info"></i>

                      <p>Service Reports</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link" to="/reports/sales">
                      <i class="far fa-circle nav-icon text-info"></i>

                      <p>Sale Reports</p>
                    </router-link>
                  </li>
                </ul>
              </li>-->

              <li class="nav-item" v-if="can('service-list')">
                <router-link class="nav-link" to="/services">
                  <i class="nav-icon orange fas fa-tasks"></i>
                  <p>SERVICES</p>
                </router-link>
              </li>

              <li class="nav-item tree1" v-if="can('staff-list')" >
                <a class="nav-link" @click="tree1">
                  <i class="nav-icon fas fa-file-pdf text-info"></i>
                  <p>
                    Reports
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link class="nav-link" to="/reports/services">
                      <i class="far fa-circle nav-icon text-info"></i>

                      <p>Service Reports</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link" to="/reports/sales">
                      <i class="far fa-circle nav-icon text-info"></i>

                      <p>Sale Reports</p>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li class="nav-item tree2" v-if="can('item-list')">
                <a class="nav-link"  @click="tree2">
                  <i class="nav-icon fas fa-database text-white"></i>
                  <p>
                    Items
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <router-link class="nav-link" to="/item">
                      <i class="far fa-circle nav-icon text-white"></i>

                      <p>Item</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link" to="/itemtype">
                      <i class="far fa-circle nav-icon text-white"></i>

                      <p>Item Type</p>
                    </router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link" to="/attribute">
                      <i class="far fa-circle nav-icon text-white"></i>

                      <p>Attribute</p>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li class="nav-item">
                <router-link to="/profile" class="nav-link">
                  <i class="fas fa-user-cog nav-icon indigo"></i>
                  <p>Account Setting</p>
                </router-link>
              </li>

              <li class="nav-item logout">
                <router-link class="nav-link" to="/logout">
                  <i class="nav-icon red fas fa-power-off"></i>
                  <p>LOG OUT</p>
                </router-link>
              </li>
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper bgBody">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <div class="content pt-4">
          <div class="container-fluid py-2">
            
              <router-view></router-view>
            
            
          </div>
          <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
      <footer class="main-footer bg-white">
        <div class="text-center">
          <strong>Copyright &copy;Microstack Technology Co.ltd</strong>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
import Login from "./Login.vue";
import Dashboard from "./Dashboard.vue";

export default {
  components: {
    Login,
    Dashboard
  },
  data() {
    return {
      User: {
        sale: "",
        service: "",
        develop: "",
        can: ""
      },
      isLoggedIn: "",
      username: ""
    };
  },
  methods: {
    ServiceShow() {
      return this.User.sale || this.User.service ? true : false;
    },
    SaleShow() {
      return this.User.sale ? true : false;
    },
    StaffShow() {
      return this.User.develop ? true : false;
    },
    tree1(e) {
      if ($(".tree1 .nav-treeview").css("display") == "block") {
        $(".tree1 .nav-link .right")
          .css("-ms-transform", "rotate(0deg)")
          .css("transform", "rotate(0deg)");
        $(".tree1 .nav-treeview").animate({ height: "0px" }, 500, function() {
          $(".tree1 .nav-treeview").css("display", "none");
        });
      } else {
        $(".tree1 .nav-treeview").css("display", "block");
        $(".tree1 .nav-treeview").css("height", "0px");
        $(".tree1 .nav-link .right")
          .css("-ms-transform", "rotate(-90deg)")
          .css("transform", "rotate(-90deg)");

        $(".tree1 .nav-treeview").animate({ height: "92px" }, 500, function() {
          $(".tree1 .nav-treeview").css("overflow", "none");
        });
      }
    },
    tree2(e) {
      if ($(".tree2 .nav-treeview").css("display") == "block") {
        $(".tree2 .nav-link .right")
          .css("-ms-transform", "rotate(0deg)")
          .css("transform", "rotate(0deg)");
        $(".tree2 .nav-treeview").animate({ height: "0px" }, 500, function() {
          $(".tree2 .nav-treeview").css("display", "none");
        });
      } else {
        $(".tree2 .nav-link .right")
          .css("-ms-transform", "rotate(-90deg)")
          .css("transform", "rotate(-90deg)");
        $(".tree2 .nav-treeview").css("display", "block");
        $(".tree2 .nav-treeview").css("height", "0px");
        $(".tree2 .nav-treeview").animate({ height: "144px" }, 500, function() {
          $(".tree2 .nav-treeview").css("overflow", "none");
        });
      }
    }
  },
  created() {
    this.auth();
    this.username = User.getUser();
    this.isLoggedIn = User.isLoggedIn();

    Bus.$emit("getrole");
    Bus.$on("logout", function() {
      User.clear();
      this.isLoggedIn = false;
      window.location.replace("/");
    });
  },
  filters: {
    username: function(value) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    }
  }
};
</script>
<style scoped>
@import url("https://fonts.googleapis.com/css?family=Roboto|Ubuntu");

.pushmenu:hover {
  cursor: pointer;
}
.nav-item {
  cursor: pointer;
}
input {
  font-weight: 700 !important;
}

.nav-item .nav-link p {
  font-weight: 400;
  font-size: 12px;
  font-family: "Roboto", sans-serif;
}
.info a {
  font-family: "Ubuntu", sans-serif;
}
.logout {
  border-top: 1px solid rgba(200, 200, 200, 0.3);
}
@media print {
  .main-footer {
    display: none;
  }
}
</style>