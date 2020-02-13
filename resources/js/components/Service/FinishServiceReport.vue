<template>
  <div v-if="show">
    <unauthorized v-if="!authorized"></unauthorized>

    <div class="invoice-box" v-else>
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2">
            <table>
              <tr>
                <td class="title">
                  <img :src="'/images/logo/1.png'" alt="microstack" width="300px" />
                </td>

                <td>
                  Invoice : Mrs-{{service.id}}
                  <br />
                  Created: {{ service.created_at | myDate}}
                  <br />
                  Due: {{ service.updated_at | myDate}}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="information">
          <td colspan="2">
            <table>
              <tr>
                <td>
                  <h5 class="pb-2">Company Information</h5>Microstack
                  <br />63*64,Kantkaw Street
                  <br />Theitpan Street
                </td>

                <td>
                  <h5 class="pb-2">Customer Information</h5>Acme Corp.
                  <br />John Doe
                  <br />john@example.com
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div class="row">
              <div class="callout callout-info mb-4 col-6">
                <h6 class="mb-3 heading">Received Description</h6>
                {{ service.description}}
              </div>

              <div class="callout callout-info mb-4 col-6">
                <h6 class="mb-3 heading">Received Remarks</h6>
                {{ service.remark}}
              </div>
            </div>
          </td>
        </tr>
        <tr class="heading">
          <td>Item</td>

          <td>Price</td>
        </tr>

        <tr class="item">
          <td>Website design</td>

          <td>$300.00</td>
        </tr>

        <tr class="item">
          <td>Hosting (3 months)</td>

          <td>$75.00</td>
        </tr>

        <tr class="item last">
          <td>Domain name (1 year)</td>

          <td>$10.00</td>
        </tr>

        <tr class="total">
          <td></td>

          <td>Total: $385.00</td>
        </tr>
      </table>
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
      show: false,
      authorized: false,
      service: ""
    };
  },
  methods: {
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
      this.$router.go(-1);
    },
    shows() {
      this.show = true;
    }
  },
  created() {
    if (User.isLoggedIn()) {
      axios.post("/api/auth/me").then(response => {
        Gate.setUser(response.data.user.roles, response.data.user.permissions);

        if (!(Gate.isSaleperson() || Gate.isServiceEngineer())) {
          this.shows();
          this.authorized = false;
        } else {
          this.shows();
          this.authorized = true;
        }
      });
    }

    this.loadService();
  }
};
</script>

<style scoped>
.callout {
  background-color: #eff0f1;
  border-left: 3px solid #7e7e7e;
}
.invoice-box {
  margin: auto;
  padding: 30px;
  border: 1px solid #eee;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
  font-size: 16px;
  line-height: 24px;

  color: #555;
}

.invoice-box table {
  width: 100%;
  line-height: inherit;
  text-align: left;
}

.invoice-box table td {
  padding: 5px;
  vertical-align: top;
}

.invoice-box table tr td:nth-child(2) {
  text-align: right;
}

.invoice-box table tr.top table td {
  padding-bottom: 20px;
}

.invoice-box table tr.top table td.title {
  font-size: 45px;
  line-height: 45px;
  color: #333;
}

.invoice-box table tr.information table td {
  padding-bottom: 40px;
}

.invoice-box table tr.heading td {
  background: #eee;
  border-bottom: 1px solid #ddd;
  font-weight: bold;
}

.invoice-box table tr.details td {
  padding-bottom: 20px;
}

.invoice-box table tr.item td {
  border-bottom: 1px solid #eee;
}

.invoice-box table tr.item.last td {
  border-bottom: none;
}

.invoice-box table tr.total td:nth-child(2) {
  border-top: 2px solid #eee;
  font-weight: bold;
}

@media only screen and (max-width: 600px) {
  .invoice-box table tr.top table td {
    width: 100%;
    display: block;
    text-align: center;
  }

  .invoice-box table tr.information table td {
    width: 100%;
    display: block;
    text-align: center;
  }
}

/** RTL **/
.rtl {
  direction: rtl;
  font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial,
    sans-serif;
}

.rtl table {
  text-align: right;
}

.rtl table tr td:nth-child(2) {
  text-align: left;
}
</style>