<template>
  <div v-if="show" >
    <unauthorized v-if="!can('service-create')"></unauthorized>
    <div  v-else class="container formcolor animated zoomIn">
      <div class="row ">
        <div class="col-12">
          <h3 class="pt-3">ADD NEW SERVICE</h3>
        </div>
      </div>
      <hr>
      <div class="row mt-4">
        <div class="col-12 ">
          <form @submit.prevent="addNewService">
            <!-- <div class="form-group row">
              <div class="col-md-4">
                <multiselect
                  :options="customers"
                  v-model="form.customer_id"
                  track-by="name"
                  :block-keys="['Delete']"
                  @input="makeDisable()"
                  placeholder="Choose Existing Customer"
                  label="name"
                ></multiselect>
              </div>
            </div> -->
            <label >Customer Information</label>
            <div class="form-group row">
              
              <div class="col-md-4">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Customer Name"
                  v-model="form.customer_name"
                  :disabled="disable"
                >
                <div
                  class="error"
                  v-for="(error,index) in form_errors['customer_name']"
                  :key="index"
                >{{ error }}</div>
              </div>
              <div class="col-md-4">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Customer Phone"
                  v-model="form.customer_phone"
                  :disabled="disable"
                >
                <div
                  class="error"
                  v-for="(error,index) in form_errors['customer_phone']"
                  :key="index"
                >{{ error }}</div>
              </div>
              <div class="col-md-4">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Customer Address"
                  v-model="form.customer_address"
                  :disabled="disable"
                >
                <div
                  class="error"
                  v-for="(error,index) in form_errors['customer_address']"
                  :key="index"
                >{{ error }}</div>
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-6">
                <label >Received By</label>
                <multiselect
                  v-model="form.receive_staff"
                  track-by="name"
                  :block-keys="['Delete']"
                  placeholder="Choose Received Staff"
                  label="name"
                  :options="receptionists"
                ></multiselect>

                <div
                  class="error"
                  v-for="(error,index) in form_errors['receive_staff']"
                  :key="index"
                >{{ error }}</div>
              </div>
              <div class="col-lg-4 col-md-5 offset-md-1 offset-lg-2">
                <label >Received Date</label>
                <datetime format="YYYY-MM-DD H:i:s" v-model="form.date" name="date" required ></datetime>

                <div
                  class="error"
                  v-for="(error,index) in form_errors['date']"
                  :key="index"
                >{{ error }}</div>
              </div>
            </div>

            <div class="form-group">
              <label for="description">Error Description</label>
              <editor
               :init="{menubar:false,
                       statusbar: false,
                       toolbar : false}"
                name="description"
                id="description"
                cols="30"
                rows="5"
                class="form-control tiny"
                v-model="form.description"
              ></editor>
              <div
                class="error"
                v-for="(error,index) in form_errors['description']"
                :key="index"
              >{{ error }}</div>
            </div>

            <div class="form-group">
              <label for="remark">Received Description</label>
              <editor
                :init="{menubar:false,
                       statusbar: false,
                       toolbar : false}"
                name="remark"
                id="remark"
                cols="30"
                rows="5"
                class="form-control"
                v-model="form.remark"
              ></editor>
              <div
                class="error"
                v-for="(error,index) in form_errors['remark']"
                :key="index"
              >{{ error }}</div>
            </div>
            <div class="form-group text-right">
              <button class="btn btn-secondary" @click="close">Back</button>
              <button type="submit" class="btn btn-primary">Save</button>
              
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Unauthorized403 from "../errors/Unauthorized403";
import datetime from 'vuejs-datetimepicker';

export default {
  components: {
    Unauthorized: Unauthorized403,
    datetime 
  },
  data() {
    return {
      
      disable: false,
      receptionists: [],
      customers: [],
      form: {
        customer_id: "",
        customer_name: "",
        customer_phone: "",
        customer_address: "",
        receive_staff: "",
        description: "",
        remark: "",
        date : ""
      },
      form_errors: []
    };
  },
  methods: {
    loadReceptionists() {
      axios
        .get("/api/receptionists")
        .then(response => {
          this.receptionists = response.data;
          
        })
        .catch(error => {
          console.log(error.response);
        });
    },
    loadCustomers() {
      axios
        .get("/api/customers")
        .then(response => {
          
          this.customers = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    addNewService() {
      if (this.form.receive_staff)
        this.form.receive_staff = this.form.receive_staff.id;
      if (this.form.customer_id) {
        this.form.customer_id = this.form.customer_id.id;
      }

      if (this.form_errors.length > 0) {
        return;
      }
      axios
        .post("/api/services", this.form)
        .then(response => {
          Toast.fire({
            type: "success",
            title: response.data
          });
          if (response.status == 200) {
            this.$router.push("/services");
          }
        })
        .catch(error => {
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
          
          if (error.response.status == 422) {
            
            this.form_errors = error.response.data.errors;
            this.form.receive_staff = this.receptionists.find(staff => {
              return staff.no === this.form.receive_staff;
            });
            
          }
        });
    },
    close() {
      this.$router.push("/services");
    },
    makeDisable() {
      if (this.form.customer_id) {
        this.disable = true;
      } else {
        this.disable = false;
      }
    }
  },

  created() {
    // if (User.isLoggedIn()) {
    //   axios.post("/api/auth/me").then(response => {
    //     Gate.setUser(response.data.user.roles,response.data.user.permissions);
    //     if (!Gate.isSaleperson()) {
    //       this.shows();
    //       this.authorized = false;
    //     } else {
    //       this.shows();
    //       this.authorized = true;
    //     }
    //   });
    // }
    this.auth();

    this.loadReceptionists();
    this.loadCustomers();
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scope>
#tj-datetime-input{
  height: 43px;
}
.tox-notifications-container{
  display : none !important;
}
@media (max-width: 768px) {
  .col-md-4 {
    margin-bottom: 15px;
  }
}
.error {
  color: red;
}
.formcolor {
  
    background: #EBEFF2;
    border-left: 2px solid rgba(113, 190, 152, 0.271);
    border-right: 2px solid rgba(113, 190, 152, 0.271);
    border-top: 20px solid rgba(113, 190, 152, 0.271);
    border-bottom: 2px solid rgba(113, 190, 152, 0.271);
    border-radius:30px;
}
</style>
