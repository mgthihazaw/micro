<template>
<div v-if="show">
  <unauthorized v-if="!can('item-create')"></unauthorized>
  <div class="row" v-else>
    <div class="mb-2 col-md-12 row">
      <div class="col-md-5">
        <button class="btn btn-outline-success btn-lg" @click.prevent="createType">
          Add New
          <i class="fa fa-plus"></i>
        </button>
      </div>
      <h3 class="px-4 pt-2 col-md-4 text-left">ITEM TYPE TABLE </h3>
    </div>

    <div class="col-md-12 table-scroll">
      <table class="table table-hover ">
        <tbody>
          <tr class="heading">
            <th style="width: 200px">#</th>
            <th>Name</th>
            
            <th style="width: 200px">Action</th>
          </tr>
          <tr v-for="(type,index) in itemTypes" :key="index" class="animated fadeIn">
            <td>{{ paginationData.meta.from + index }}</td>
            <td>{{ type.name }}</td>
            
            <td>
              <button class="btn btn-warning btn-sm text-white" @click="editType(type)">
                <i class="fa fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm" @click="deleteType(type.id)">
                <i class="fa fa-times"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-12 mt-3">
      <pagination :data="paginationData" @pagination-change-page="getItemTypes"></pagination>
    </div>
    
    
    <!------------------------MODAL DIALOG------------------------>
    <div
      class="modal fade"
      id="newType"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" v-if="!edit">Create New Item Type</h5>
            <h5 class="modal-title" id="exampleModalLabel" v-else>Edit Item Type</h5>
            <button type="button" class="close" @click="cancel">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="POST">
              <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" v-model="form.name" />
                <div class="error text-muted" v-if="errors.name">Item type name field is required</div>
              </div>
            </form>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cancel">Close</button>
              <button type="submit" class="btn btn-primary" @click="saveType" v-if="!edit">Create</button>
              <button type="submit" class="btn btn-primary" @click="updateType" v-else>Update</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!------------------------MODAL DIALOG------------------------>
  </div>
  </div>
</template>

<script>
import Unauthorized403 from "../errors/Unauthorized403";
export default {
  components: {
    Unauthorized: Unauthorized403
  },
  data() {
    return {
      edit: false,
      paginationData: {},
      itemTypes: [],
      form: {
        id: "",
        name: ""
      },
      errors: ""
    };
  },
  methods: {
    saveType() {
      axios
        .post("api/itemtypes", this.form)
        .then(res => {
          this.cancel();
          Toast.fire({
            type: "success",
            title: "Item Type is Successfully Created"
          });
          Bus.$emit("afterTypeCreated");
        })
        .catch(err => {
          Toast.fire({
            type: "error",
            title: err.response.data.message
          });
          this.errors = err.response.data.errors;

          
        });
    },
    deleteType(id) {
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
            axios.delete("/api/itemtypes/" + id).then(res => {
              Bus.$emit("afterTypeDeleted");
              swal.fire(
                "Deleted!",
                "Item Type is Successfully Deleted",
                "success"
              );
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
    editType(type) {
      this.edit = true;
      $("#newType").modal("show");
      this.form.name = type.name;
      this.form.id = type.id;
    },
    updateType() {
      axios
        .put(`api/itemtypes/${this.form.id}`, this.form)
        .then(res => {
          this.cancel();
          Toast.fire({
            type: "success",
            title: "Item Type is Updated"
          });
          Bus.$emit("afterTypeCreated");
        })

        .catch(err => {
          Toast.fire({
            type: "error",
            title: err.response.data.message
          });
          this.errors = err.response.data.errors;

          
        });

      
    },

    createType() {
      $("#newType").modal("show");
    },
    cancel() {
      $("#newType").modal("hide");
      this.form = {
        id: "",
        name: ""
      };
      this.edit = false;
    },
    getItemTypes(page = 1) {
      axios.get("/api/itemtypes?page=" + page).then(response => {
        this.paginationData = response.data;
        this.itemTypes = response.data.data;
      });
    }
  },
  created() {
    this.auth();
    this.getItemTypes();
    Bus.$on("afterTypeCreated", () => {
      this.getItemTypes();
    });
    Bus.$on("afterTypeDeleted", () => {
      this.getItemTypes();
    });
  },
  beforeDestroy() {
    //While Modal is opened Route change has modal errors.That is dixed this error
    $("#newType").modal("hide");
    $(document.body).removeClass("modal-open");
    $(document.body).css('padding-right',"0px")
    $(".modal-backdrop").remove();
  }
};
</script >


<style scoped>
.table-scroll {
  overflow-x: auto;
  overflow-y: hidden;
}
.table {
  width: 100%;
  height: 100%;
  
  font-size: 15px;

  line-height: 1.2;
  font-weight: unset !important;
  padding-top: 20px;

  padding-bottom: 20px;
}
table {
  border-collapse: collapse;
  border-radius: 1em;
  overflow: hidden;
}
.heading {
  background: rgb(168, 180, 176);
}
.heading th {
  padding: 20px;
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
.error {
  color: #d8000c !important;
  margin-top: 2px;
  font-style: italic;
}

.formcolor {
  background: #ebeff2;
  border-left: 2px solid rgba(113, 190, 152, 0.271);
  border-right: 2px solid rgba(113, 190, 152, 0.271);
  border-top: 20px solid rgba(113, 190, 152, 0.271);
  border-bottom: 2px solid rgba(113, 190, 152, 0.271);
  border-radius: 30px;
}
</style>
