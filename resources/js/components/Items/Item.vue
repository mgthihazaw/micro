<template>
  <div v-if="show">
    <unauthorized v-if="!can('item-create')"></unauthorized>

    <div v-else>
      <div class="row">
        <div class="itemTable col-md-12 table-scroll" v-if="!(create || edit)" ref="itemTable">
          <div class="mb-2 row">
            <div class="col-md-5">
              <button class="btn btn-outline-success btn-lg" @click="createItem">
                Add New
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <h3 class="px-4 pt-2 col-md-4 text-left">ITEM TABLE</h3>
          </div>

          <!-- /.card-header -->
          <div class="p-0 col-md-12">
            <table class="table table-hover">
              <tbody>
                <tr class="heading">
                  <th style="width: 50px">#</th>
                  <th>Name</th>
                  <th>Item Type</th>
                  <th>Attributes</th>
                  <th>Price</th>
                  
                  <th style="width: 150px">Action</th>
                </tr>
              </tbody>
              <tbody v-for="(item,index) in items" :key="index" class="animated fadeIn">
                <tr>
                  <td style="width: 50px">{{ paginationData.meta.from + index }}</td>
                  <td>
                    {{ item.brand.name }}
                    <span>{{ item.model_no }}</span>
                  </td>

                  <td>{{ item.item_type.name }}</td>

                  <td>
                    <span
                      v-for="(attribute,index) in item.attributes"
                      :key="index"
                    >{{ attribute.name }} &nbsp;</span>
                  </td>

                  <td>{{ item.price }}</td>
                  
                  <td style="width: 200px">
                    <button
                      class="btn btn-info btn-sm text-white"
                      data-toggle="collapse"
                      :data-target="`#demo${index}`"
                    >
                      <i class="fa fa-eye"></i>
                    </button>
                    <button class="btn btn-warning btn-sm text-white" @click="editItem(item.id)">
                      <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" @click="deleteItem(item.id)">
                      <i class="fa fa-times"></i>
                    </button>
                  </td>
                </tr>
                <tr :id="`demo${index}`" class="collapse animated fadeIn">
                  <td colspan="6">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">
                          {{ item.brand.name }}
                          <span>{{ item.model_no }}</span>
                          &nbsp;&nbsp;&nbsp; => &nbsp;&nbsp;&nbsp;&nbsp;
                          {{ item.price }}
                        </h4>

                        <p
                          v-for="(attribute,index) in item.attributes"
                          :key="index"
                          class="card-text"
                        >{{ attribute.attribute_group}} : {{attribute.name}}</p>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <pagination :data="paginationData" @pagination-change-page="getItems"></pagination>
          </div>
          <!-- /.card-body -->
        </div>

        <!--------------------ITEM CREATE FORM------------------------------------>

        <div class="itemForm col-12" v-if="create">
          <div class="container formcolor animated zoomIn">
            <div class="row">
              <div class="col-12">
                <h3 class="pt-3">
                  <i class="fas fa-arrow-circle-left green" @click="back" style="cursor: pointer"></i>
                  CREATE ITEM
                </h3>
              </div>
            </div>
            <hr />
            <div class="row justify-content-center mt-4">
              <div class="col-10">
                <form>
                  <h5>1.Insert Item Information</h5>

                  <div class="form-group row pt-4">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Item Type</label>
                    </div>
                    <div class="col-md-9">
                      <multiselect
                        :options="itemTypes"
                        label="name"
                        v-model="itemType"
                        placeholder="Choose Item Type"
                        :taggable="true"
                        :block-keys="['Delete']"
                        @select="loadAttributeGroup"
                        @input="getBrand"
                      ></multiselect>
                      <div
                        class="error text-muted"
                        v-if="errors.item_type_id"
                      >Item type field is required</div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="secret" class="pt-2">Item Brand</label>
                    </div>
                    <div class="col-md-9">
                      <multiselect
                        tag-placeholder="Create New Brand"
                        :options="brands"
                        label="name"
                        v-model="brand"
                        placeholder="Choose Brand Name"
                        :taggable="true"
                        :block-keys="['Delete']"
                        @tag="addBrandTag"
                      ></multiselect>
                      <div class="error text-muted" v-if="errors.brand">Item brand field is required</div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Model No</label>
                    </div>
                    <div class="col-md-9">
                      <input
                        type="text"
                        class="form-control"
                        id="exampleInputtext1"
                        v-model="form.model_no"
                        placeholder="Enter Model Number"
                      />
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Item Price</label>
                    </div>
                    <div class="col-md-9">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="price"
                        v-model="form.price_one"
                      />
                      <div class="error text-muted" v-if="errors.price_one">Item price1 is required</div>
                    </div>
                    <!-- <div class="col-md-3">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="Enter Price Two(optional)"
                        v-model="form.price_two"
                      />
                    </div>
                    <div class="col-md-3">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="Enter  Price Three(optional)"
                        v-model="form.price_three"
                      />
                    </div> -->
                  </div>

                  <div v-if="attributeGroups.length>0">
                    <hr />
                    <h5 class="py-3">2.Insert Item Attributes Information</h5>
                    <div
                      class="form-group row"
                      v-for="(attributeGroup,index) in attributeGroups"
                      :key="index"
                    >
                      <div class="col-md-3">
                        <label for="exampleInputtext1" class="pt-2">{{ attributeGroup.name }}</label>
                      </div>

                      <div class="col-md-9">
                        <multiselect
                          :options="attributeGroup.attributes"
                          v-model="attributes[index]"
                          placeholder="Choose Attribute"
                          label="name"
                          :close-on-select="true"
                          :show-labels="false"
                          :block-keys="['Delete']"
                          :taggable="true"
                          @tag="addTag($event,index,attributeGroup.attributes)"
                        ></multiselect>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row pt-3">
                    <div class="col-md-5"></div>
                    <div class="col-md-4">
                      <button class="btn btn-secondary" @click="back">Back</button>
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="submitItem(form.id)"
                      >Save Item</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!--------------------ITEM CREATE FORM------------------------------------>

        <!--------------------ITEM EDIT FORM------------------------------------>

        <div class="itemForm col-12" v-if="edit">
          <div class="container formcolor animated zoomIn">
            <div class="row">
              <div class="col-12">
                <h3 class="pt-3">
                  <i class="fas fa-arrow-circle-left green" @click="back" style="cursor: pointer"></i>
                  EDIT ITEM
                </h3>
              </div>
            </div>
            <hr />
            <div class="row justify-content-center mt-4">
              <div class="col-10">
                <form>
                  <h5>1.Insert Item Information</h5>

                  <div class="form-group row pt-4">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Item Type</label>
                    </div>
                    <div class="col-md-9">
                      <multiselect
                        v-model="itemType"
                        track-by="name"
                        :block-keys="['Delete']"
                        placeholder="Choose State"
                        label="name"
                        :options="itemTypes"
                        :reset-after="false"
                        @selected = "getBrand"
                        @input="loadAttributeGroup"
                      ></multiselect>
                      <div
                        class="error text-muted"
                        v-if="errors.item_type_id"
                      >Item type field is required</div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="secret" class="pt-2">Item Brand</label>
                    </div>
                    <div class="col-md-9">
                      <multiselect
                        v-model="brand"
                        track-by="name"
                        :block-keys="['Delete']"
                        placeholder="Choose Brand"
                        label="name"
                        :options="brands"
                        :reset-after="false"
                        :taggable="true"
                        @tag="addBrandTag"
                      ></multiselect>
                      <div class="error text-muted" v-if="errors.brand">Item brand field is required</div>
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Model No</label>
                    </div>
                    <div class="col-md-9">
                      <input
                        type="text"
                        class="form-control"
                        id="exampleInputtext1"
                        v-model="form.model_no"
                        placeholder="Enter Model Number"
                      />
                    </div>
                  </div>

                  <div class="form-group row">
                    <div class="col-md-3">
                      <label for="exampleInputtext1" class="pt-2">Item Price</label>
                    </div>
                    <div class="col-md-9">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="price"
                        v-model="form.price"
                      />
                      <div class="error text-muted" v-if="errors.price_one">Item price1 is required</div>
                    </div>
                    <!-- <div class="col-md-3">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="Enter Item Price Two"
                        v-model="form.price_two"
                      />
                    </div>
                    <div class="col-md-3">
                      <input
                        type="number"
                        class="form-control"
                        id="exampleInputtext1"
                        placeholder="Enter Item Price Three"
                        v-model="form.price_three"
                      />
                    </div> -->
                  </div>

                  <div v-if="attributeGroups.length>0">
                    <hr />
                    <h5 class="py-3">2.Insert Item Attributes Information</h5>
                    <div
                      class="form-group row"
                      v-for="(attributeGroup,index) in attributeGroups"
                      :key="index"
                    >
                      <div class="col-md-3">
                        <label for="exampleInputtext1" class="pt-2">{{ attributeGroup.name }}</label>
                      </div>

                      <div class="col-md-9">
                        <multiselect
                          :options="attributeGroup.attributes"
                          v-model="attributes[index]"
                          placeholder="Choose Attribute"
                          :block-keys="['Delete']"
                          label="name"
                          track-by="name"
                          :taggable="true"
                          @tag="addTag($event,index,attributeGroup.attributes)"
                        ></multiselect>
                      </div>
                    </div>
                  </div>

                  <div class="form-group row pt-3">
                    <div class="col-md-5"></div>
                    <div class="col-md-4">
                      <button type="button" class="btn btn-secondary" @click="back">Back</button>
                      <button
                        type="button"
                        class="btn btn-primary"
                        @click="updateItem(form.id)"
                      >Update Item</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!--------------------ITEM EDIT FORM------------------------------------>
        <!-------------SHOW --------------------->

        <!-------------SHOW --------------------->
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
      edit: false,
      create: false,
      items: [],
      itemTypes: [],
      itemType: [],
      brands: [],
      brand: "",
      attributeGroups: [],
      paginationData: {},
      attributes: [],
      form: {
        id: "",
        brand: "",
        price_one: "",
        model_no: "",
        item_type_id: "",
        attributes: []
      },
      errors: ""
    };
  },
  methods: {
    loadItemType() {
      axios
        .get(`/api/itemtypes`)
        .then(res => {
          this.itemTypes = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },
    loadAttributeGroup(type) {
      
      if (type) {
        axios
          .get(`/api/itemtypes/${type.id}/attributegroups`)
          .then(res => {
            this.attributeGroups = res.data.data;
            this.form.attributes = [];
            this.attributeGroups.forEach((attGroup, index) => {
              let name = attGroup.name;
              this.attributes[index] = "";
              // this.form.attributes[attGroup.name]=''
              // this.form.attributes=[]
              this.form.attributes.push({ [attGroup.name]: "" });
            });
          })
          .catch(err => {
            console.log(err);
          });
      }
    },
    getItems(page = 1) {
      axios
        .get("/api/items?page=" + page)
        .then(res => {
          this.paginationData = res.data;
          this.items = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
    },

    getItem(id) {
      axios
        .get("/api/items/" + id)
        .then(res => {
          this.form = res.data.data;
          
          this.brand = this.form.brand;
          this.itemType = this.form.item_type;
          const tempAttributes = res.data.data.attributes;
          axios
            .get(`/api/itemtypes/${this.itemType.id}/attributegroups`)
            .then(res => {
              this.attributeGroups = res.data.data;
              this.form.attributes = [];
              this.attributeGroups.forEach((attGroup, index) => {
                this.form.attributes.push({ [attGroup.name]: "" });

                this.attributes[index] = tempAttributes.find(tempAttribute => {
                  return tempAttribute.attribute_group == attGroup.name;
                });
              });
            })
            .catch(err => {
              console.log(err);
            });
        })
        .catch(err => {
          console.log(err);
        });
    },
    getBrand(type) {

      if(type){
        axios
        .get(`/api/itemtypes/${type.id}/brands`)
        .then(res => {
          this.brands = res.data.data;
        })
        .catch(err => {
          console.log(err);
        });
      }
    },
    createItem() {
      this.create = true;
    },
    editItem(id) {
      this.edit = true;

      this.getItem(id);
    },

    submitItem() {
      this.form.item_type_id = this.itemType.id;
      this.form.brand = this.brand.name;

      //Validation Of Attribute
      this.form.attributes.forEach((attribute, key) => {
        if (this.attributes[key]) {
          attribute[Object.keys(attribute)] = this.attributes[key].name;
        }
      });

      this.form.attributes = this.form.attributes.filter(function(
        value,
        index,
        arr
      ) {
        return (
          value[Object.keys(value)] != "" && value[Object.keys(value)] != null
        );
      });
      

      axios
        .post("/api/items", this.form)
        .then(res => {
         

          this.back();

          Toast.fire({
            type: "success",
            title: "Successfully Created"
          });
          Bus.$emit("afterItemChange");
        })
        .catch(err => {
          

          Toast.fire({
            type: "error",
            title: err.response.data.message
          });
          this.errors = err.response.data.errors;

          
        });
    },
    deleteItem(id) {
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
            axios.delete("/api/items/" + id).then(res => {
              Bus.$emit("afterItemChange");
              swal.fire("Deleted!", "Successfully  Deleted", "success");
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
    updateItem(id) {
      this.form.item_type_id = this.itemType.id;
      this.form.brand = this.brand.name;
      this.form.price_one = this.form.price;

      //Validation Of Attribute
      this.form.attributes.forEach((attribute, key) => {
        if (this.attributes[key]) {
          attribute[Object.keys(attribute)] = this.attributes[key].name;
        }
      });

      this.form.attributes = this.form.attributes.filter(function(
        value,
        index,
        arr
      ) {
        return (
          value[Object.keys(value)] != "" && value[Object.keys(value)] != null
        );
      });
      

      axios
        .put("/api/items/" + id, this.form)
        .then(res => {
         

          this.back();

          Toast.fire({
            type: "success",
            title: "Successfully Updated"
          });
          Bus.$emit("afterItemChange");
        })
        .catch(err => {
          Toast.fire({
            type: "error",
            title: err.response.data.message
          });
          this.errors = err.response.data.errors;

          
        });
    },
    showItem(id) {
      $("#showItem").modal("show");
    },
    back() {
      this.$router.push("/item");
      this.create = false;
      this.edit = false;
      this.form = {
        id: "",
        brand_id: "",
        quantity: "",
        price_one: "",
        price_two: "",
        price_three: "",
        model_no: "",
        item_type_id: "",
        attributes: []
      };
      this.itemType = [];
      this.brand = [];
      this.attributeGroups = [];
      this.attributes = [];
    },
    addBrandTag(newTag) {
      let data_id = 0;
      if (this.brands.length > 0) {
        data_id = this.brands.length;
      }
      const tag = {
        name: newTag,
        id: data_id
      };
      this.brands.push(tag);
      this.brand = tag;
    },

    addTag(newTag, attribute, attributes) {
      let attr_id = 0;
      if (attributes.length > 0) {
        attr_id = attributes.length;
      }
      const tag = {
        name: newTag,
        id: attr_id
      };
      
      attributes.push(tag);
      this.attributes[attribute] = tag;
    }
  },
  created() {
    this.auth();
    this.getItems();
    this.loadItemType();
    
    Bus.$on("afterItemChange", () => {
      this.getItems();
    });
  }
};
</script >

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.table-scroll {
  overflow-x: auto;
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
