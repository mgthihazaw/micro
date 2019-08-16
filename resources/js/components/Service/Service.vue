<template>
  <div v-if="show">
    <unauthorized v-if="!authorized"></unauthorized>

    <div class="container_fluid" v-else>
      <div>
        <div class="printPage">
          <div class="row pageHeader">
            <div class="mt-3 col-6">
              <img :src="'/images/logo/1.png'" alt="microstack" width="200px" />
            </div>
            <div class="col-6 pt-2">
              <div class="row">
                <div class="col-3">
                  <h6 class="text-dark">Invoice #</h6>
                </div>
                <div class="col-9">
                  <p class="text-dark">MI-{{service.invoice_id}}</p>
                </div>
              </div>

              <div class="row">
                <div class="col-3">
                  <h6 class="text-dark">Date</h6>
                </div>
                <div class="col-9">
                  <p class="text-dark">{{ service.created_at | myDate}}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <div>
              <div class="row customerParent">
                <div class="col-6">
                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-dark">Customer Name</h6>
                    </div>
                    <div class="col-6">
                      <p class="text-dark">{{ service.customer_name}}</p>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-dark">Customer Phone</h6>
                    </div>
                    <div class="col-6">
                      <p class="text-dark">{{ service.customer_phone}}</p>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-6">
                      <h6 class="text-dark">Customer Address</h6>
                    </div>
                    <div class="col-6">
                      <p class="text-dark">{{ service.customer_address}}</p>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="row">
                    <div class="col-12">
                      <p class="text-dark">
                        တ-၂/၇ ပိေတာက္လမ္း၊၆၃လမ္း၊၆၄လမး္ၾကား ပိေတာက္လမ္းၾကား
                        <br />ခ်မ္းျမသာစည္ျမိဳ႕နယ္၊မႏၱေလး။ ၀၉-၉၁၀၂၄၂၄၃ ၊ ၀၉-၉၇၃၅၃၇၆၅၆
                      </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <p class="text-dark">
                        အမွတ္ ၂၃-ေျမညီထပ္(A)၊သမိန္ပရမ္းလမ္းတို၊နတ္ေခ်ာင္းရပ္ကြက္
                        <br />တာေမြျမိဳ႕နယ္၊ရန္ကုန္။ ၀၉-၇၉၁၇၂၂၇၂၂ ၊ ၀၉-၇၈၅၅၅၇၉၄၄
                      </p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="staff">
                <div>
                  <div class="row">
                    <div class="col-2">
                      <h6 class="text-dark">Received By</h6>
                    </div>
                    <div class="col-10">
                      <p class="text-dark">{{ service.staff}}</p>
                    </div>
                  </div>

                  <div class="row">
                    <div class="des mb-4 col-6">
                      <h6 class="mb-3">Error Description</h6>
                      <span v-html="service.received_description"></span>
                    </div>

                    <div class="des mb-4 col-6">
                      <h6 class="mb-3">Received Description</h6>
                      <span v-html="service.received_remark"></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="des mb-4 col-6">
                      <h6 class="mb-3">Service Description</h6>
                      <span v-html="service.service_description"></span>
                    </div>

                    <div class="des mb-4 col-6">
                      <h6 class="mb-3">Service Remarks</h6>
                      <span v-html="service.service_remark"></span>
                    </div>
                  </div>

                  <div class="p-0">
                    <table class="table table-hover">
                      <tbody>
                        <tr class="heading" v-if="serviceItems.length>0">
                          <th style="width: 30px">No.</th>
                          <th>Name</th>

                          <th>Quantity</th>
                          <th>Unit Price</th>
                          <th>Amount</th>
                          <th></th>
                        </tr>

                        <tr
                          v-for="(item,index) in serviceItems"
                          :key="index"
                          class="animated fadeIn"
                        >
                          <td>{{ index+1 }}</td>
                          <td class="ml-2">
                            {{ item.brand.name }}
                            <span
                              v-if="item.moden_no != '' && item.attributes.length < 1"
                            >{{ item.model_no }}</span>
                            <span
                              v-else
                              v-for="(attribute,index) in item.attributes"
                              :key="index"
                            >{{ attribute.name }} &nbsp;</span>
                          </td>

                          <td class="ml-2">
                            <input
                              type="number"
                              min="1"
                              class="form-control"
                              v-model="item.quantity"
                              @input="this.Bus.$emit('addItemForService',item)"
                            />
                            <span class="inputData">{{ item.quantity }}</span>
                          </td>

                          <td class="ml-2">
                            <input
                              type="number"
                              min="0"
                              v-model="item.price"
                              class="form-control"
                              @input="this.Bus.$emit('addItemForService',item)"
                            />
                            <span class="inputData">{{ item.price }}</span>
                          </td>
                          <td class="ml-2">
                            <input type="number" min="0" v-model="item.amount" class="form-control" />
                            <span class="inputData">{{ item.amount }}</span>
                          </td>
                          <td>
                            <button class="btn btn-danger btn-sm" @click="deleteItem(index)">
                              <i class="fas fa-minus"></i>
                            </button>
                          </td>
                        </tr>

                        <tr v-if="serviceItems.length > 0">
                          <td>
                            <p class="text-bold"></p>
                          </td>
                          <td style="padding-left:30px;"></td>
                          <td></td>
                          <td>Total</td>
                          <td style="padding-left:10px;">
                            <p class="text-bold">{{ getTotal }}</p>
                          </td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- /.card -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-11">
            <div class="button_group pb-4">
              <button class="btn btn-secondary" @click="back()">Back</button>
              <button
                type="button"
                class="btn btn-info text-white"
                data-toggle="modal"
                data-target="#modal-lg"
              >
                Add Item
                <i class="ml-2 fas fa-plus-circle fa-1x"></i>
              </button>
              <button class="btn btn-primary" @click="save">Save</button>
            </div>
          </div>
          <div class="col-md-1 text-right">
            <button
                class="btn btn-success"
                @click="print"
                v-if="(service.pending == 3 || service.pending == 4) && printBtnDisplay  && serviceItems.length > 0"
              >Print <i class="fa fa-print"></i></button>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal-lg" style="display: none;" aria-hidden="true" ref="modal">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title text-info">Add Item for Service</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body bg-light">
              <search></search>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- Modal-->
    </div>
  </div>
</template>

<script>
import Unauthorized403 from "../errors/Unauthorized403";
import Search from "./Search";

export default {
  components: {
    unauthorized: Unauthorized403,
    Search
  },
  data() {
    return {
      total: 0,
      show: false,
      authorized: false,
      service: "",
      serviceItems: [],
      printBtnDisplay: true
    };
  },
  methods: {
    loadService() {
      let id = this.$route.params.id;
      axios
        .get("/api/services/" + id + "/items")
        .then(res => {
          this.service = res.data.data;
          if (this.service.items.length > 0) {
            this.service.items.forEach(item => {
              let newData = { ...item, amount: item.price };
              this.serviceItems.push(newData);
            });
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    back() {
      this.$router.push("/services");
    },
    shows() {
      this.show = true;
    },

    deleteItem(index) {
      this.printBtnDisplay = false;
      this.serviceItems.splice(index, 1);
    },
    print() {
      window.print();
      axios
        .put(`/api/services/${this.service.id}`)
        .then(res => {
          this.service = res.data;
        })
        .catch(error => {
          console.log("Error here", error);
        });
    },
    save() {
      let data = [];

      this.serviceItems.forEach(serviceItem => {
        let newData = {
          item_id: serviceItem.id,
          quantity: serviceItem.quantity,
          price: serviceItem.price
        };
        data.push(newData);
      });

      axios
        .post(`/api/services/${this.service.id}/items`, data)
        .then(res => {
          this.service = res.data;
          this.printBtnDisplay = true;
        })
        .catch(err => {
          console.log(err);
        });
    }
  },
  created() {
    Bus.$on("addItemForService", item => {
      this.printBtnDisplay = false;
      item = { ...item, quantity: 1, amount: item.price };

      this.serviceItems.push(item);
      this.serviceItems = this.serviceItems.reduce((serviceItem, current) => {
        const x = serviceItem.find(item => item.id === current.id);
        if (!x) {
          return serviceItem.concat([current]);
        } else {
          return serviceItem;
        }
      }, []);
    });
    if (User.isLoggedIn()) {
      axios.post("/api/auth/me").then(response => {
        Gate.setUser(response.data.user.roles, response.data.user.permissions);

        if (!Gate.isSaleperson()) {
          this.shows();
          this.authorized = false;
        } else {
          this.shows();
          this.authorized = true;
        }
      });
    }

    this.loadService();
  },
  watch: {
    serviceItems: {
      handler: function(after, before) {
        before.forEach(data => {
          data.amount = data.quantity * data.price;
        });
      },
      deep: true
    }
  },
  computed: {
    getTotal() {
      this.total = 0;
      let data;

      data = this.serviceItems.forEach(serviceItem => {
        this.total += serviceItem.amount;
        return this.total;
      });
      return this.total;
    }
  },
  beforeDestroy() {
    //While Modal is opened Route change has modal errors.That is dixed this error
    $("#modal-lg").modal("hide");
    $(document.body).removeClass("modal-open");
    $(document.body).css("padding-right", "0px");
    $(".modal-backdrop").remove();
  }
};
</script>


<style scoped>
h1,
h2,
h3,
h4,
h5 {
  font-weight: 500;
  line-height: 1.4em;
}
h6 {
  font-weight: bold;
  line-height: 1.4em;
}

.pageHeader {
  margin: 0;
  padding: 0px;
  border-bottom: 1px solid #7e7e7e;
}
.staff {
  padding-top: 20px;
  border-top: 1px solid #7e7e7e;
}
.des {
  background-color: #ffffff;
  border-left: 3px solid #59997b;
  padding: 10px;
}
@media print {
  .printBtn {
    display: none;
  }
  button {
    display: none;
  }
  input {
    display: none;
  }
  span.inputData {
    display: block;
  }
  .des {
    display: none;
  }
  .saveform {
    display: none;
    margin: 0;
  }
  .printPage {
    margin: 50px;
  }
}
.tb {
  padding: 0;
  margin: 0;
}
.inputData {
  display: none;
}
</style>