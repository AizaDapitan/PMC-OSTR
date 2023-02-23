<template>
  <div class="container-fluid pd-x-0" id="printForm">
    <div
      class="
        d-sm-flex
        align-items-center
        justify-content-between
        mg-b-20 mg-lg-b-25 mg-xl-b-30
      "
    >
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb breadcrumb-style1 mg-b-10">
            <li class="breadcrumb-item" aria-current="page">OSTR</li>
            <li class="breadcrumb-item" aria-current="page">Stock Request</li>
            <li class="breadcrumb-item active" aria-current="page">
              Edit Request
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Edit Stock Request</h4>
      </div>
    </div>
    <div v-if="errors_exist" class="alert alert-danger" role="alert">
      Whoops! Something didn't work.
      <ul>
        <div v-for="error in errors" :key="error.id">
          <li>{{ error[0] }}</li>
        </div>
      </ul>
    </div>
    <div v-if="success" class="alert alert-success" role="success">
      <b>Transaction Completed!</b> {{ successMsg }}
      <!-- <div v-for="error in errors" :key="error.id"> -->
      <!-- {{ }} -->
      <!-- </div> -->
    </div>
    <div class="col-lg-12 d-flex justify-content-start justify-content-lg-end">
      <button
        class="btn btn-primary tx-13 btn-uppercase mr-2 mb-2 ml-lg-1 mr-lg-0"
        id="print"
      >
        <i data-feather="save" class="mg-r-5"></i> Print
      </button>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <div class="d-lg-flex justify-content-lg-end">
          <div class="col-lg-3">
            <div class="form-group">
              <label for="transnumber">Transaction # </label>
              <input
                type="text"
                class="form-control"
                id="transnumber"
                name="transnumber"
                v-model="form.transaction_no"
                disabled="true"
              />
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row row-sm">
          <div class="col-lg-6">
            <div class="form-group">
              <label for="date-filed"
                >Date Filed<span class="text-danger" aria-required="true">
                  *
                </span></label
              >
              <input
                type="text"
                class="form-control"
                id="date-filed"
                name="date-filed"
                pattern="\d{2}\/\d{2}\/\d{4}"
                placeholder="mm/dd/yyyy"
                disabled="true"
              />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="time-filed"
                >Time Filed<span class="text-danger" aria-required="true">
                  *
                </span></label
              >
              <input
                type="time"
                class="form-control"
                id="time-filed"
                name="time-filed"
                v-model="form.time_filed"
                disabled="true"
              />
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="dept"
            >Department<span class="text-danger" aria-required="true">
              *
            </span></label
          >
          <input
            type="text"
            class="form-control"
            id="dept"
            name="dept"
            v-model="form.dept"
            disabled="true"
          />
        </div>
        <div class="form-group">
          <label for="requested-by"
            >Requested By<span class="text-danger" aria-required="true">
              *
            </span></label
          >
          <input
            type="text"
            class="form-control"
            id="requested-by"
            name="requested-by"
            v-model="form.requested_by"
            disabled="true"
          />
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row row-sm">
          <div class="col-lg-6">
            <div class="form-group">
              <label for="date-needed"
                >Date Needed<span class="text-danger" aria-required="true">
                  *
                </span></label
              >
              <input
                type="text"
                class="form-control"
                id="date-needed"
                name="date-needed"
                pattern="\d{2}\/\d{2}\/\d{4}"
                placeholder="mm/dd/yyyy"
              />
            </div>
          </div>

          <!-- <div class="col-lg-6">
            <div class="form-group">
              <label for="costcode"
                >Cost Code<span class="text-danger" aria-required="true">
                  *
                </span></label
              >
              <input
                type="text"
                class="form-control"
                id="costcode"
                name="costcode"
                v-model="form.cost_code"
              />
            </div>
          </div> -->
          <div class="col-lg-6">
            <div class="form-group">
              <label for="origin"
                >Origin<span class="text-danger" aria-required="true">
                  *
                </span></label
              >
              <select
                v-model="selectedValue"
                class="custom-select"
                @change="getData($event)"
              >
                <option selected>MCD</option>
                <option
                  v-for="satellite in satellites"
                  :key="satellite.name"
                  :value="satellite.name"
                >
                  {{ satellite.name }}
                </option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="remarks">Remarks</label>
          <textarea
            rows="2"
            class="form-control"
            id="remarks"
            name="remarks"
            v-model="form.remarks"
          />
        </div>
      </div>
      <div class="col-lg-12">
        <hr class="mg-t-10 mg-b-30" />
      </div>
    </div>
    <div class="row row-xs">
      <div class="col-lg-12">
        <div class="table-responsive-lg">
          <DataTable
            ref="dt"
            :value="form.items"
            stripedRows
            :rowsPerPageOptions="[10, 20, 50]"
            responsiveLayout="scroll"
            :loading="loading"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            v-model:filters="filters"
            filterDisplay="menu"
            rowIndexVar
            editMode="row"
            dataKey="id"
            v-model:editingRows="editingRows"
            @row-edit-save="onRowEditSave"
          >
            <template #empty> No record found. </template>
            <template #loading> Loading data. Please wait. </template>

            <Column header="Item No.">
              <template #body="slotProps">
                {{ slotProps.index + 1 }}
              </template></Column
            >
            <Column field="id" hidden="true"></Column>
            <Column field="stock_code" header="Stock Code"></Column>
            <!-- <Column field="available_qty" header="Available Qty."></Column> -->
            <Column field="uom" header="UoM"></Column>
            <Column field="description" header="Description"></Column>
            <Column field="requested_qty" header="Requested Qty.">
              <template #editor="{ data, field }">
                <InputText
                  v-model="data[field]"
                  type="number"
                  min="0"
                  autofocus
                />
              </template>
            </Column>

            <Column
              :rowEditor="true"
              style="width: 7%; min-width: 8rem"
              bodyStyle="text-align:center"
            ></Column>
            <Column :exportable="false">
              <template #body="slotProps">
                <!-- <Button
                  v-bind:title="edititem"
                  icon="pi pi-pencil"
                  class="p-button-rounded p-button-success mr-2"
                  @click="editItem(slotProps)"
                /> -->
                <Button
                  v-bind:title="deleteitem"
                  icon="pi pi-trash"
                  class="p-button-rounded p-button-warning mr-2"
                  @click="deleteItem(slotProps)"
                />
              </template>
            </Column>
          </DataTable>
        </div>
      </div>
      <div class="col-lg-12" style="margin-top: 8px" id="addItem">
        <div class="row row-sm">
          <div class="col-lg-3">
            <div class="form-group">
              <AutoComplete
                v-model="selectedProduct_stock_code"
                :suggestions="filteredProducts"
                @complete="searchProduct($event, 'stock_code')"
                style="width: 100%; line-height: 0.5"
                inputStyle="width:100%"
                field="code"
                id="stock_code"
                @item-select="onChange($event, 'stock_code')"
                placeholder="Stock Code"
              />
            </div>
          </div>
          <div class="col-lg-1">
            <div class="form-group">
              <input
                type="text"
                class="form-control"
                id="uom"
                name="uom"
                placeholder="Uom"
                disabled="true"
              />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="form-group">
              <AutoComplete
                v-model="selectedProduct_desc"
                :suggestions="filteredProducts"
                @complete="searchProduct($event, 'description')"
                style="width: 100%; line-height: 0.5"
                inputStyle="width:100%"
                field="name"
                id="description"
                @item-select="onChange($event, 'description')"
                placeholder="Description"
              />
            </div>
          </div>
          <div class="col-lg-2">
            <div class="form-group">
              <input
                type="number"
                class="form-control"
                id="requested_qty"
                name="requested_qty"
                placeholder="Requested Qty."
                min="0"
              />
            </div>
          </div>
          <div class="col-lg-2">
            <div class="form-group">
              <button
                class="
                  btn btn-primary
                  tx-13
                  btn-uppercase
                  mr-2
                  mb-2
                  ml-lg-1
                  mr-lg-0
                "
                @click="addItem"
              >
                <i data-feather="plus" class="mg-r-5"></i> Add Item
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- row -->

    <hr class="mg-t-30 mg-b-30" id="hr" />

    <div class="row flex-column-reverse flex-lg-row" id="actions">
      <div class="col-lg-6">
        <a :href="dashboard" class="btn btn-white tx-13 btn-uppercase"
          ><i data-feather="arrow-left" class="mg-r-5"></i> Back to Dashboard</a
        >
      </div>
      <div class="col-lg-6 d-flex justify-content-start justify-content-lg-end">
        <button
          type="submit"
          class="btn btn-primary tx-13 btn-uppercase mr-2 mb-2 ml-lg-1 mr-lg-0"
          @click.prevent="save"
        >
          <i data-feather="save" class="mg-r-5"></i> Save
        </button>
        <button
          type="submit"
          class="btn btn-danger tx-13 btn-uppercase mr-2 mb-2 ml-lg-1 mr-lg-0"
          @click.prevent="submitRequest"
        >
          <i data-feather="send" class="mg-r-5"></i> Submit
        </button>
      </div>
    </div>

    <div class="cms-footer mg-t-50">
      <hr />
      <p class="tx-gray-500 tx-10">
        Admin Portal v1.0 • Developed by WebFocus Solutions, Inc. 2022
      </p>
    </div>
  </div>
  <toast
    :breakpoints="{ '920px': { width: '100%', right: '0', left: '0' } }"
  ></toast>
  <DynamicDialog />
  <ConfirmDialog></ConfirmDialog>
  <Dialog
    header="Error"
    v-model:visible="displayError"
    :style="{ width: '350px' }"
    :modal="true"
  >
    <div class="confirmation-content">
      <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
      <span>{{ this.errMsg }}</span>
    </div>
    <template #footer>
      <Button label="Ok" @click="closeModal" class="p-button-text" autofocus />
    </template>
  </Dialog>
</template>
    <script>
import item from "../../components/item/item";
import { h } from "vue";
import Button from "primevue/button";
import * as Printjs from "print-js";
export default {
  props: ["request"],
  data() {
    return {
      dashboard: this.$env_Url + "/stockrequests/dashboard",
      loading: true,
      success: false,
      errors_exist: false,
      seconds: 0,
      errors: {},
      products: [],
      filteredProducts: null,
      selectedProduct_desc: null,
      selectedProduct_stock_code: null,
      displayError: false,
      errMsg: "",
      editingRows: [],
      satellites: [],
      selectedValue: "",
      form: {
        id: this.request.id,
        dept: this.request.dept,
        date_filed: "",
        time_filed: "",
        date_needed: "",
        cost_code: this.request.cost_code,
        remarks: this.request.remarks,
        requested_by: this.request.requested_by,
        transaction_no: this.request.transaction_no,
        items: [],
        origin: this.request.origin,
      },
    };
  },
  created() {
    this.fetchItems();
    this.fetchPublishedProduct();
    this.fetchSatellites();
  },
  mounted() {
    document.getElementById("date-needed").value = this.request.date_needed;
    document.getElementById("date-filed").value = this.request.date_filed;
    if (this.request.time_filed != null) {
      this.form.time_filed = this.request.time_filed.replace(":00.0000000", "");
    }
    this.selectedValue = this.request.origin;
  },

  methods: {
    async fetchItems() {
      const res = await this.callApiwParam(
        "post",
        "/requested_items/getRequestedItemsSaved",
        this.form
      );
      this.form.items = res.data;
      this.loading = false;
    },
    async fetchItemsUnsaved() {
      const res = await this.callApiwParam(
        "post",
        "/requested_items/getRequestedItems",
        this.form
      );
      this.form.items = res.data;
      this.loading = false;
    },
    async fetchPublishedProduct() {
      const res = await this.getDataFromDB(
        "get",
        "/products/getPublishedProducts"
      );
      this.products = res.data;
    },
    async fetchSatellites() {
      const res = await this.getDataFromDB("get", "/satellites/getSatellites");
      this.satellites = res.data;
    },
    async save() {
      this.errors = {};
      this.errors_exist = false;
      if (this.form.items.length === 0) {
        this.errors = {
          error: ["Please add atleast 1 item!"],
        };
        this.errors_exist = true;
      } else {
        this.form.date_needed = document.getElementById("date-needed").value;
        this.form.datefiled = document.getElementById("date-filed").value;

        const res = await this.submit(
          "post",
          "/stockrequests/update",
          this.form,
          {
            headers: {
              "Content-Type":
                "multipart/form-data; charset=utf-8; boundary=" +
                Math.random().toString().substr(2),
            },
          }
        );
        if (res.status === 200) {
          this.success = true;
          this.successMsg = "Request successfully saved.";
          this.isSaved = true;
        } else {
          this.errors_exist = true;
          this.errors = res.data.errors;
        }
      }
    },
    async submitRequest() {
      this.errors = {};
      this.errors_exist = false;
      if (this.form.items.length === 0) {
        this.errors = {
          error: ["Please add atleast 1 item!"],
        };
        this.errors_exist = true;
      } else {
        this.form.date_needed = document.getElementById("date-needed").value;
        this.form.datefiled = document.getElementById("date-filed").value;

        const res = await this.submit(
          "post",
          "/stockrequests/submit",
          this.form,
          {
            headers: {
              "Content-Type":
                "multipart/form-data; charset=utf-8; boundary=" +
                Math.random().toString().substr(2),
            },
          }
        );
        if (res.status === 200) {
          // this.success = true;
          // this.successMsg = "Request successfully submitted.";
          window.location.href = this.$env_Url + "/stockrequests/dashboard";
        } else {
          this.errors_exist = true;
          this.errors = res.data.errors;
        }
      }
    },
    showDialog(data) {
      const dialogRef = this.$dialog.open(item, {
        props: {
          header: "Request Items",
          style: {
            width: "35vw",
          },
          breakpoints: {
            "960px": "75vw",
            "640px": "90vw",
          },
          modal: true,
        },
        data: {
          id: data.id,
          products: this.products,
          requested_by: this.form.requested_by,
          stock_code: data.stock_code,
          available_qty: data.available_qty,
          uom: data.uom,
          description: data.description,
          requested_qty: data.requested_qty,
        },
        onClose: (options) => {
          this.fetchItemsUnsaved();
        },
      });
    },

    reset() {
      this.form.date_needed = null;
      this.form.cost_code = "";
      this.form.remarks = "";
    },
    deleteItem(data) {
      // let src = data.data.id,
      //   alt = data.data.id;
      // this.form.itemID = alt;

      this.$confirm.require({
        message: "Do you want to delete this item?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: async () => {
          // const res = await this.deleteRecord(
          //   "post",
          //   "/requested_items/delete",
          //   {
          //     id: data.data.id,
          //   }
          // );
          // if (res.status === 200) {
          //   this.rmessage();
          //   this.fetchItems();
          // } else {
          //   this.ermessage();
          // }

          // this.form.items = this.form.items.filter(
          //   (obj) => obj.length != data.index
          // );
          this.form.items.splice(data.index, 1);
        },
      });
    },
    editItem(data) {
      // this.showDialog(data.data);
    },
    async autosave() {
      this.seconds = this.seconds + 1;
      if (this.seconds == 60) {
        if (this.form.transmittalno != "") {
          if (this.form.purpose == null) {
            this.form.purpose = "";
          }
          if (this.form.email_address == null) {
            this.form.email_address = "";
          }
          if (this.form.source == null) {
            this.form.source = "";
          }
          this.form.date_needed = document.getElementById("date-needed").value;
          this.form.datefiled = document.getElementById("date-filed").value;
          let form = new FormData();
          form.append("cocFile", this.COCitemFile);
          _.each(this.form, (value, key) => {
            form.append(key, value);
          });
          const res = await this.submit("post", "/officer/autosave", form, {
            headers: {
              "Content-Type":
                "multipart/form-data; charset=utf-8; boundary=" +
                Math.random().toString().substr(2),
            },
          });
          if (res.status === 200) {
            this.form.id = res.data.id;
          }
        }
        this.seconds = 0;
      }
    },
    searchProduct(event, field) {
      setTimeout(() => {
        if (!event.query.trim().length) {
          this.filteredDescription = [...this.products];
        } else {
          this.filteredProducts = this.products.filter((product) => {
            if (field == "description") {
              var name = product.name;
              if (name == null) {
                name = "";
              }
              return name
                .toLowerCase()
                .startsWith(event.query.toLowerCase());
            } else {
              var code = product.code;
              if (code == null) {
                code = "";
              }
              return code.toLowerCase().startsWith(event.query.toLowerCase());
            }
          });
        }
      }, 250);
    },
    onChange(event, field) {
      if (field == "description") {
        document.getElementById("stock_code").value =
          this.selectedProduct_desc.code;
        document.getElementById("description").value =
          this.selectedProduct_desc.name;
        document.getElementById("uom").value = this.selectedProduct_desc.uom;
      } else {
        document.getElementById("stock_code").value =
          this.selectedProduct_stock_code.code;
        document.getElementById("description").value =
          this.selectedProduct_stock_code.name;
        document.getElementById("uom").value =
          this.selectedProduct_stock_code.uom;
      }
    },
    addItem() {
      var error = "";
      if (document.getElementById("uom").value == "") {
        error = "Required Field: Item not found!";
        this.singleermessage(error);
      } else if (document.getElementById("stock_code").value == "") {
        error = "Required Field: Stock Code is required!";
        this.singleermessage(error);
      } else if (document.getElementById("description").value == "") {
        error = "Required Field: Description is required!";
        this.singleermessage(error);
      } else if (
        document.getElementById("requested_qty").value == "" ||
        document.getElementById("requested_qty").value == 0
      ) {
        error = "Required Field: Requested Qty. is required!";
        this.singleermessage(error);
      }

      if (error == "") {
        const obj = {
          id: this.form.items.length,
          stock_code: document.getElementById("stock_code").value,
          description: document.getElementById("description").value,
          uom: document.getElementById("uom").value,
          requested_qty: document.getElementById("requested_qty").value,
        };
        this.form.items.push(obj);

        document.getElementById("stock_code").value = "";
        document.getElementById("description").value = "";
        document.getElementById("uom").value = "";
        document.getElementById("requested_qty").value = "";

        this.filteredProducts = null;
        this.selectedProduct_desc = null;
        this.selectedProduct_stock_code = null;
        this.isSaved = false;
      }
    },
    onRowEditSave(event) {
      let { newData, index } = event;

      this.form.items[index] = newData;
    },
    closeModal() {
      this.displayError = false;
    },
    getData(event) {
      this.form.origin = event.target.value;
    },
    print() {
      print();
      //  Printjs({
      //    printable: "print-form",
      //    type: "HTML"
      // });
    },
  },
};
</script>

<style scoped lang="scss">
// .p-button {
//   margin: 0.3rem 0.5rem;
//   min-width: 10rem;
// }

p {
  margin: 0;
}

.confirmation-content {
  display: flex;
  align-items: center;
  justify-content: center;
}

.p-dialog .p-button {
  min-width: 6rem;
}

</style>