<template>
  <div v-if="show">
    <unauthorized v-if="!can('staff-list')"></unauthorized>
    <div class="col-12 animated fadeIn" v-else>
      <div class="search_form">
        <form @submit.prevent="loadService">
          <div class="form-group row pt-2">
            <div class="col-md-5">
              <h4 class="p-2 text-info">Search For Service Reports :</h4>
            </div>
            <div class="col-md-3">
              <multiselect
                v-model="brand"
                track-by="name"
                :block-keys="['Delete']"
                placeholder="Choose Service"
                label="name"
                selectLabel="Select"
                deselectLabel="Deselect"
                :options="brands"
                :reset-after="false"
              ></multiselect>
            </div>
            <div class="col-md-3">
              <vue-monthly-picker v-model="selectedMonth" placeHolder="Select MonthOfYear"></vue-monthly-picker>
            </div>
            <div class="col-md-1">
              <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
          </div>
        </form>
      </div>

      <div class="itemTable">
        <div class="p-0 col-md-12">
          <table class="table table-hover" v-if="datas.length >0">
            <tbody>
              <tr class="heading">
                <th style="width: 50px">No</th>
                <th>Name</th>

                <th>Total Price</th>

                <th>Date</th>
              </tr>
              <tr class="heading" v-for="(data,index) in datas" :key="index">
                <td>{{ index+1 }}</td>
                <td>{{ data.name}}</td>
                <td>{{ data.total }}</td>
                <td>{{ data.date }}</td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <h6 class="text-info">Total Price of Month</h6>
                </td>
                <td>
                  <h6 class="text-info">{{ totalPrice }}</h6>
                </td>
                <td>
                  <button class="btn btn-primary btn-sm print_btn" @click="this.print()" >
                    <i class="fas fa-print"></i> PDF
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VueMonthlyPicker from "vue-monthly-picker";
import moment from "moment";
import Unauthorized403 from "../errors/Unauthorized403";

export default {
  components: {
    VueMonthlyPicker: VueMonthlyPicker,
    Unauthorized: Unauthorized403
  },
  data() {
    return {
      datas: [],
      brands: [],
      brand: "",
      selectedMonth: null,
      serviceDatas: [],
      totalPrice: 0,
      searchForm: {
        brand: "",
        date: ""
      }
    };
  },
  methods: {
    getBrand() {
      axios
        .get(`/api/brands?item_type=Service`)
        .then(res => {
          this.brands = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    loadService() {
      this.datashow = false;
      this.serviceDatas = [];
      this.searchForm = {
        brand: "",
        date: ""
      };

      if (this.brand) {
        this.searchForm.brand = this.brand.id;
      }
      if (this.selectedMonth) {
        this.searchForm.date = this.selectedMonth.format("YYYY-MM");
      }

      axios
        .get("/api/reports/services", { params: this.searchForm })
        .then(res => {
          this.serviceDatas = res.data;
          this.datas = [];
          let date;
          if (this.serviceDatas.length > 0) {
            date = new Date(this.serviceDatas[0].date);
          } else if (this.searchForm.date) {
            date = new Date(this.searchForm.date);
          } else {
            date = new Date();
          }

          let y = date.getFullYear();
          let m = ("0" + (date.getMonth() + 1)).slice(-2);

          for (let i = 0; i < this.getDaysInMonth(m, y); i++) {
            let d=("0" + (i + 1)).slice(-2)
            this.datas[i] = {
              
              date: `${y}-${m}-${d}`,
              name: ".....",
              total: "0"
            };
            this.serviceDatas.find(service => {
              if (new Date(service.date).getDate() == i + 1) {
                if(this.brand){
                  this.datas[i] = {
              date: service.date,
              name: this.brand.name,
              total: service.total
            };
                }else{
                  this.datas[i] = service;
                }
                
              }
            });
          }
        });
    },
    getDaysInMonth(month, year) {
      return parseInt(new Date(year, month, 0).getDate());
    }
  },
  watch: {
    datas(s) {
      this.totalPrice = 0;
      s.forEach(data => {
        this.totalPrice += parseInt(data.total);
      });
    }
  },

  created() {
    this.loadService();
    this.auth();
    this.getBrand();
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style >
.vue-monthly-picker .input {
  padding: 20px !important;
}
.table {
  font-family: Poppins-Regular;
  font-size: 15px;
  color: #666666;
  line-height: 1.2;
  font-weight: unset !important;
}
table {
  font-family: Poppins-Regular;
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

@media print {
  .search_form {
    display :none;
  }
  .print_btn {
    display : none;
  }
  .table{
    font-size :14px;
  }
}
</style>
