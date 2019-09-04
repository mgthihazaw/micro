<template>
  <div v-if="show">
    <unauthorized v-if="!can('staff-create')"></unauthorized>
    <div v-else>
      <div class="row" v-if="(!createMode && !editMode)">
        <div class="col-12  table-scroll">
          <div class="mb-2 row">
            <div class="col-md-5" v-if="can('staff-create')">
              <button class="btn btn-outline-success btn-lg" @click="create">
                Add New
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <h3 class="px-4 pt-2 col-md-4 text-left">Staffs List</h3>
          </div>

          <table class="table table-hover">
            <thead>
              <tr class="heading">
                <th>No</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Business/Office</th>
                <th>Department</th>
                <th>Role/Job Title</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(staff,index) in staffs" :key="staff.id" class="animated fadeIn">
                <td>{{ paginationData.meta.from + index }}</td>
                <td>{{ staff.name }}</td>
                <td>{{ staff.phone }}</td>
                <td>{{ staff.address}} ၊  {{ staff.township.name }}</td>
                <td>{{ staff.business.name }}</td>
                <td>{{ staff.department.name }}</td>
                <td>{{ staff.role.name }}</td>
                <td>
                  <button class="btn btn-info btn-sm text-white" @click="editStaff(staff)">
                    <i class="fa fa-edit"></i>
                  </button>
                  <button class="btn btn-danger btn-sm" @click="deleteStaff(staff.id)">
                    <i class="fa fa-times"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
          <pagination :data="paginationData" @pagination-change-page="getStaffs"></pagination>
          <br />
          <br />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>

<script>
import Unauthorized403 from "./errors/Unauthorized403";
import CreateStaff from "./Staff/CreateStaff.vue";
import EditStaff from "./Staff/EditStaff.vue";

export default {
  components: {
    CreateStaff,
    EditStaff,
    Unauthorized: Unauthorized403
  },
  data() {
    return {
      show: false,
      authorized: false,
      createMode: false,
      editMode: false,
      edit: false,
      staffs: {},
      editstaff: {},
      paginationData: {}
    };
  },
  methods: {
    editStaff(staff) {
      this.editMode = true;
      this.editstaff = staff;

      this.$router.push(`/staffs/edit/${staff.id}`);
    },
    deleteStaff(id) {
     
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
            axios.delete("/api/staffs/" + id).then(response => {
              Toast.fire({
                type: "success",
                title: "Successfully Deleted"
              });
              Bus.$emit("afterDeleted");
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
    getStaffs(page = 1) {
      axios.get("/api/staffs?page=" + page).then(res => {
        this.paginationData = res.data;
        this.staffs = res.data.data;
      });
    },
    create() {
      this.createMode = true;
      this.$router.push("/staffs/create");
    }
  },
  created() {
    this.auth();

    this.getStaffs();
    Bus.$on("afterCreated", () => {
      this.getStaffs();
    });

    Bus.$on("afterUpdated", () => {
      this.getStaffs();
    });

    Bus.$on("afterDeleted", () => {
      this.getStaffs();
    });

    Bus.$on("cancel", () => {
      this.createMode = false;
      this.editMode = false;
    });
  }
};
</script>
<style scoped>
.table-scroll {
  overflow-x: auto;
}

.table {
  font-size: 15px;
  color: #666666;
  line-height: 1.2;
  font-weight: unset !important;
}
table {
  border: 1px solid rgb(111, 161, 136);
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

/* .createroute {
  display: block;
  float: right;
} */
</style>
