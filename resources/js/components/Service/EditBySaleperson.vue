<template>
  <div
    class="container formcolor animated zoomIn"
    v-if="this.show"
  >
    <div class="row formheading">
      <div class="col-12">
        <h3>Edit by saleperson</h3>
      </div>
    </div>
    <hr />
    <div class="row mt-4">
      <div class="col-12">
        <form @submit.prevent="updateService">
          <label>Customer Information</label>
          <div class="form-group row">
            <div class="col-md-4">
              <input
                type="text"
                class="form-control"
                placeholder="Customer Name"
                v-model="form.customer_name"
                :disabled="disable"
              />
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
              />
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
              />
              <div
                class="error"
                v-for="(error,index) in form_errors['customer_address']"
                :key="index"
              >{{ error }}</div>
            </div>
          </div>

          <div class="form-group row">
            <div class="col-md-6">
              <label>Received By</label>
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

            <div class="col-md-6">
              <label>Received Date</label>
              <datetime
                format="YYYY-MM-DD H:i:s"
                width="100%"
                v-model="form.date"
                name="date"
                required
              ></datetime>

              <div
                class="error"
                v-for="(error,index) in form_errors['date']"
                :key="index"
              >{{ error }}</div>
            </div>
          </div>

          <div v-if="service.pending == 0">
            <div class="form-group">
              <label for="description">Error Description</label>
              <editor
                :init="{
                      menubar:false,
                       statusbar: false,
                       toolbar : false
                     }"
                name="description"
                id="description"
                cols="30"
                rows="5"
                class="form-control"
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
          </div>

          <div class="row" v-else>
            <div class="col-md-6">
              <div class="form-group">
                <label for="description">Error Description</label>
                <editor
                  :init="{
                      menubar:false,
                       statusbar: false,
                       toolbar : false
                     }"
                  name="description"
                  id="description"
                  cols="30"
                  rows="5"
                  class="form-control"
                  v-model="form.description"
                ></editor>
                <div
                  class="error"
                  v-for="(error,index) in form_errors['description']"
                  :key="index"
                >{{ error }}</div>
              </div>
            </div>

            <div class="col-md-6">
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
            </div>

            <div class="col-12">
              <label class="mb-3">Check Results</label>
              <span style="color : red" v-html="service.check_results"></span>
            </div>
          </div>

          <div class="form-group text-right">
            <button class="btn btn-secondary" @click="close">Back</button>
            <button type="submit" class="btn btn-primary" v-if="service.pending == 0">Update</button>
            <button type="button" class="btn btn-danger" @click="cancelService" v-if="service.pending == 1">Cancel</button>
            <button type="submit" class="btn btn-success" v-if="service.pending == 1">Proceed</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <unauthorized v-else></unauthorized>
</template>

<script>
import Unauthorized403 from "../errors/Unauthorized403";
import datetime from "vuejs-datetimepicker";
export default {
  components: {
    datetime,
    unauthorized: Unauthorized403
  },
  data() {
    return {
      disable: false,
      service: "",
      show: true,
      receptionists: [],
      form: {
        customer_name: "",
        customer_phone: "",
        customer_address: "",
        receive_staff: "",
        description: "",
        remark: "",
        date: ""
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
          console.log(error);
        });
    },
    loadService() {
      let id = this.$route.params.id;

      axios.get(`/api/services/${id}`).then(res => {
        this.service = res.data.data;

        if (this.service.customer_id) {
          this.disable = true;
        }
        this.form.customer_name = this.service.customer_name;
        this.form.customer_phone = this.service.customer_phone;
        this.form.customer_address = this.service.customer_address;
        this.form.description = this.service.received_description;
        this.form.remark = this.service.received_remark;
        this.form.date = this.service.received_date;

        this.form.receive_staff = this.service.staff_id;
        this.form.receive_staff = this.receptionists.find(staff => {
          return staff.id == this.form.receive_staff;
        });
        if (
         !( this.service.pending == 0 ||
          this.service.pending == 1 ||
          this.service.pending == 2 ||
          this.service.pending == 3)
        ) {
          this.show = false;
        }
      });
    },
    updateService() {
      if (this.form.receive_staff) {
        this.form.receive_staff = this.form.receive_staff.id;
      }
      axios
        .put(`/api/services/${this.service.id}`, this.form)
        .then(res => {
          Toast.fire({
            type: "success",
            title: res.data
          });
          this.$router.push("/services");
        })
        .catch(error => {
          Toast.fire({
            type: "error",
            title: error.response.data.message
          });
          this.form.receive_staff = this.receptionists.find(staff => {
            return staff.no === this.form.receive_staff;
          });
          if (error.response.status == 422) {
            this.form_errors = error.response.data.errors;
          }
        });
    },
    cancelService(){
      if (this.form.receive_staff) {
        this.form.receive_staff = this.form.receive_staff.id;
      }
      axios.delete(`/api/services/${this.service.id}`)
        .then(res => {
          Toast.fire({
            type: "success",
            title: res.data
          });
          this.$router.push("/services");
        })
    },
    close() {
      this.$router.push("/services");
    }
  },
  created() {
    this.loadReceptionists();
    this.loadService();
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scope>
#tj-datetime-input {
  height: 43px;
}
.error {
  color: red;
}
.formcolor {
  background: #ebeff2;
  border-left: 2px solid rgba(113, 190, 152, 0.271);
  border-right: 2px solid rgba(113, 190, 152, 0.271);
  border-top: 20px solid rgba(113, 190, 152, 0.271);
  border-bottom: 2px solid rgba(113, 190, 152, 0.271);
  border-radius: 30px;
}
.formheading {
  padding-top: 10px;
}
</style>
