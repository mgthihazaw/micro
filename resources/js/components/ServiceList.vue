<template>

  <div v-if="show">
    <unauthorized v-if="!can('service-list')"></unauthorized>
    <div v-else>
      
      <div class="row">
        <div class="col-md-12 table-scroll">
          <div class="mb-2 row">
            <div class="col-md-1" v-if="can('service-create')">
              <router-link
                class="btn btn-success "
                to="/services/create"
                v-if="can('service-create')"
              >
                Add
                <i class="fa fa-plus"></i>
              </router-link>
            </div>
            
            <div class="col-md-7 mt-sm-3 mt-md-0 mt-lg-0">
              <div class="row">
                <div class="col-md-2 col-sm-2">
                  <label  style="padding-top: 8px;">Search by</label>
                </div>
                <div class="col-md-4 col-sm-4">
                  <select class='form-control' v-model="search_key" @change="showAll">
                    <option value="id">ID</option>
                    <option value="customer_name">Name</option>
                  </select>
                </div>
                <div class="col-md-6 col-sm-6 search-text">
                  <input type="text" name="search" class="form-control" v-model="search_value" @input="search_service">
                </div>
              </div>
            </div>
            <div class="col-md-1" v-if="!can('service-create')">

            </div>
            <div class="col-md-4 mt-sm-3 mt-md-0 mt-lg-0">
              <div class="row">
                <div class="col-md-3 col-sm-2">
                  <label for="service_filter" style="padding-top: 8px;">Filter by</label>
                </div>
                <div class="col-md-9 col-sm-10">
                  <select name="service_filter" id="service_filter" class="form-control" v-model="service_filter" @change="filter_service">
                    <option value=''>All</option>
                    <option value="0">Not Finished</option>
                    <option value="1">Checked</option>
                    <option value="2">Proceed</option>
                    <option value="3">Finished</option>
                    <option value="4">Hold</option>
                    <option value="5">Paid</option>
                    <option value="-1">Cancel</option>
                  </select>
                </div>
              </div>
              
              
            </div>
          </div>

          <table class="table table-hover">
            <thead>
              <tr class="heading">
                <th>No</th>
                <th>ID</th>
                <th>Customer</th>
                <th>Customer Address</th>
                <th>Customer Phone</th>
                <th>Received Staff</th>
                <th>Service Engineer</th>
                <th>Received Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(service,index) in service_list" :key="service.id" class="animated fadeIn">
                <td>{{ paginationData.meta.from + index }}</td>
                <td>{{ service.id }}</td>
                <td>{{ service.customer_name }}</td>
                <td>{{ service.customer_address }}</td>
                <td>{{ service.customer_phone }}</td>
                <td>{{ service.staff }}</td>
                <td>{{ service.service_engineer }}</td>
                <td>{{ service.received_date.substring(0, 10) }}</td>
                <td>
                  <span
                    class="badge badge-secondary"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px"
                    v-if="service.pending == -1"
                  >Cancelled</span>
                  <span
                    class="badge badge-danger"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;"
                    v-if="service.pending == 0"
                  >Not Finished</span>
                  <span
                    class="badge"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;background-color : #ff8800;color : white;"
                    v-if="service.pending == 1"
                  >Checked</span>
                  <span
                    class="badge badge-info text-white"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;"
                    v-if="service.pending == 2"
                  >Proceed</span>
                  <span
                    class="badge badge-success"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;"
                    v-if="service.pending == 3"
                  >Finished</span>
                  <span
                    class="badge badge-warning text-dark"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;"
                    v-if="service.pending == 4"
                  >Hold</span>
                  <span
                    class="badge text-white"
                    style="padding-top : 8px ; padding-bottom : 5px ; font-size : 10px;background-color : indigo"
                    v-if="service.pending == 5"
                  >Paid</span>
                </td>
                <td>
                  <button
                    class="btn btn-info btn-xs text-white"
                    @click="editServicebySaleperson(service.id)"
                    v-if="User.isSaleperson() && (service.pending == 0 || service.pending == 1 || service.pending == 2)"
                  >
                    <i class="fa fa-edit"></i>
                  </button>
                  <button
                    class="btn btn-secondary btn-xs"
                    @click="printView(service.id)"
                    v-if="User.isSaleperson() && (service.pending == 0)"
                  >
                    <i class="fas fa-print"></i>
                  </button>
                  <button
                    class="btn btn-primary btn-xs text-white"
                    @click="editServicebyServiceEngineer(service.id)"
                    v-if="User.isServiceEngineer() && (service.pending == 0 || service.pending == 2 || service.pending == 3)"
                  >
                    <i class="fa fa-edit"></i>
                  </button>

                  <router-link
                    :to="'/services/'+service.id+'/show'"
                    class="btn btn-success btn-xs text-white"
                    v-if="User.isSaleperson() && (service.pending == 3 || service.pending == 4 || service.pending == 5) "
                  >
                    <i class="fas fa-print"></i>
                  </router-link>
                 
                </td>
              </tr>
            </tbody>
          </table>
          <pagination :data="paginationData" @pagination-change-page="getServiceList"></pagination>
        </div>
      </div>
      
    </div>
  </div>
 
</template>

<script>
import Unauthorized403 from "./errors/Unauthorized403";
export default {
  components: {
    Unauthorized: Unauthorized403
  },
  data() {
    return {
      authorized: false,
      service_filter: '',
      search_key : 'id',
      search_value : '',
      User: "",
      service_list: [],
      paginationData: {}
    };
  },
  methods: {
    getServiceList(page = 1) {
      axios
        .get(`/api/services?service_filter=${this.service_filter}&search_key=${this.search_key}&search_value=${this.search_value}&page=${page}`)
        .then(response => {
          this.paginationData = response.data;
          this.service_list = response.data.data;
        })
        .catch(error => {
          if (error.response.data.type == "token_invalid") {
            alert(error.response.data.error);
            Bus.$emit("logout");
          }
        });
    },
    filter_service(){
      axios.get(`/api/services?service_filter=${this.service_filter}`)
        .then((response) => {
          this.search_key = 'id'
          this.search_value = ''
          this.paginationData = response.data;
          this.service_list = response.data.data;
        })
    },
    search_service(){
      this.service_filter = '';
      axios.get(`/api/services?search_key=${this.search_key}&search_value=${this.search_value}`)
        .then((response) => {
          this.paginationData = response.data;
          this.service_list = response.data.data;
        })
    },
    editServicebySaleperson(id) {
      this.$router.push("/services/edit/" + id);
    },
    printView(id) {
      this.$router.push({
        name: "print",
        params: { id: `${id}` },
        query: { type: "start" }
      });
    },
    editServicebyServiceEngineer(id) {
      this.$router.push("/services/edit/" + id);
    },
    deleteService(id) {
      
     
      swal
        .fire({
          title: "Are you sure to delete?",

          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!"
        })
        .then(result => {
          if (result.value) {
            //Send Delete Request to sever
            axios.delete(`/api/services/${id}`).then(response => {
              Bus.$emit("afterServiceDeleted");
            });
          }
        })
        .catch(err => {
          Toast.fire({
            type: "error",
            title: err.response.data.message
          });
        });
    },
    showAll()
    {
      this.search_value = ''
      this.getServiceList();
    }
  },

  created() {
    
    this.auth();
    this.getServiceList();
    Bus.$on("afterServiceDeleted", () => {
      this.getServiceList();
    });
  },
  filters: {
    subStr: function(string) {
      if(string){
        if (string.length > 20) {
          return string.substring(0, string.indexOf("</")) + "...";
        }
        return string;
      }
    
      return '....';
    }
  }
};
</script>

<style scoped>
@media screen and (max-width: 575px) {
  .search-text{
    margin-top : 15px;
  }
}
.table-scroll {
  overflow-x: auto;
}

/* .table{
    width:100%;
    height: 100%;
} */
.table {
  font-family: Poppins-Regular;
  font-size: 15px;
  color: #666666;
  line-height: 1.2;
  font-weight: unset !important;
}
table {
  font-family: Poppins-Regular;

  border-collapse: collapse;
  border-radius: 1em;
  overflow: hidden;
}
.heading {
  background: rgb(168, 180, 176);
}
.heading th {
  padding-top: 20px;
  padding-bottom: 20px;
}
tbody tr {
  background: rgb(243, 243, 243);
}

td {
  padding-top: 1em;
  padding-bottom: 1em;
  background: #fff;
  border-bottom: 1px solid rgba(191, 236, 197, 0.87);
}
.table-hover tbody tr:hover td {
  background: #e9ecef;
}

.createroute {
  display: block;
  float: right;
}
</style>