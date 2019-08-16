<template>
  <div v-if="show">
    <unauthorized v-if="!authorized"></unauthorized>

    <div class="container_fluid" v-else>
      <div>
        <div class="printPage">
          
          
          <div class="row pageHeader ">
            <div class="mt-1 col-6">
              <img :src="'/images/logo/1.png'" class="logo" alt="microstack" width="300px">
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
                  <p class="text-dark">{{ service.received_date | dateFilter }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-3">
            <div >
              <div class="row  customerParent ">
                <div class="col-6 ">
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
                      <p class="text-dark">တ-၂/၇ ပိေတာက္လမ္း၊၆၃လမ္း၊၆၄လမး္ၾကား ပိေတာက္လမ္းၾကား<br>ခ်မ္းျမသာစည္ျမိဳ႕နယ္၊မႏၱေလး။ ၀၉-၉၁၀၂၄၂၄၃ ၊ ၀၉-၉၇၃၅၃၇၆၅၆</p>
                    </div>
                     
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <p class="text-dark">အမွတ္ ၂၃-ေျမညီထပ္(A)၊သမိန္ပရမ္းလမ္းတို၊နတ္ေခ်ာင္းရပ္ကြက္<br>တာေမြျမိဳ႕နယ္၊ရန္ကုန္။ ၀၉-၇၉၁၇၂၂၇၂၂ ၊ ၀၉-၇၈၅၅၅၇၉၄၄</p>
                    </div>
                     
                  </div>
                </div>
                </div>
                <div class="staff ">
                  <div>
                    <div class="row">
                      <div class="col-2">
                        <h6 class="text-dark">Received By</h6>
                      </div>
                      <div class="col-10">
                        <p class=" text-dark">{{ service.staff}}</p>
                      </div>
                    </div>

                    <div class="row">
                      <div class="ml-3 callout callout-info mb-4 col-5">
                        <h6 class="mb-3">Error Description</h6>
                        <span v-html="service.received_description"></span>
                      </div>
                      <div class="col-1"></div>
                      <div class="ml-2 callout callout-info mb-4 col-5">
                        <h6 class="mb-3">Received Description</h6>
                        <span v-html="service.received_remark"></span>
                      </div>
                    </div>
                  </div>
                </div>
              
            </div>
          </div>
        </div>
      <button class="btn btn-secondary printBtn" @click="back()" >Back</button>
      <button class="btn btn-success printBtn" @click="print">Print Preview</button>
      </div>
    </div>
  </div>
</template>
<script>
import Unauthorized403 from "../errors/Unauthorized403";

export default {
  components: {
    unauthorized: Unauthorized403
  },
  data() {
    return {
      show:false,
      authorized: false,
      service: ""
    };
  },
  methods: {
      print(){
          window.print()
      },
    loadService() {
      let id = this.$route.params.id;
      axios
        .get("/api/services/" + id)
        .then(res => {
          this.service = res.data.data;
          // console.log(this.service);
        })
        .catch(err => {
          console.log(err);
        });
    },
    back() {
      
      this.$router.push("/services");
    
    },
    shows(){
      this.show=true
    }
  },
  created() {
    if (User.isLoggedIn()) {
      axios.post("/api/auth/me").then(response => {
        Gate.setUser(response.data.user.roles,response.data.user.permissions);

        if (!(Gate.isSaleperson() || Gate.isServiceEngineer())) {
          this.shows()
          this.authorized = false;
        } else {
          this.shows()
          this.authorized = true;
        }
      });
    }

    this.loadService();
  },
  filters: {
  dateFilter: function (value) {
    if (!value) return ''
    value = value.toString()
    return value.substring(0, 10)
  }
}
};
</script>

<style scoped>
h1,h2,h3,h4,h5{
  font-weight:500;
  line-height: 1.4em;
}
h6{
  font-weight:bold;
  line-height: 1.4em;
}


.pageHeader {
  margin:0;
  padding: 0px;
  border-bottom: 1px solid #7e7e7e;
}
.staff {
  padding-top: 20px;
  border-top: 1px solid #7e7e7e;
}
.callout {
  background-color: #eff0f1;
  border-left: 3px solid #7e7e7e;
  
}
@media print {
  .printBtn {
    display:none;
  }
  .main-footer{
    display:none;
  }
  .printPage {
  margin : 50px;
}
}

  

</style>
