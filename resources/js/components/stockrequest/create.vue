<template>
  <div class="container-fluid pd-x-0">
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
              Create Request
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Create Stock Request</h4>
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
    <div class="row">
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
                v-model="form.date_filed"
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

          <div class="col-lg-6">
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
      <div class="col-lg-6 d-flex justify-content-start">
        <button
          class="btn btn-primary tx-13 btn-uppercase mr-2 mb-2 ml-lg-1 mr-lg-0"
          @click="showDialog"
        >
          <i data-feather="plus" class="mg-r-5"></i> Add Item
        </button>
      </div>
      <div class="col-lg-12">
        <div class="table-responsive-lg">
          <DataTable
            ref="dt"
            :value="items"
            :paginator="true"
            :rows="10"
            stripedRows
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[10, 20, 50]"
            responsiveLayout="scroll"
            :loading="loading"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
            v-model:filters="filters"
            filterDisplay="menu"
            rowIndexVar
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
            <Column field="available_qty" header="Available Qty."></Column>
            <Column field="uom" header="UoM"></Column>
            <Column field="description" header="Description"></Column>
            <Column field="requested_qty" header="Requested Qty."></Column>

            <Column
              :exportable="false"
              style="min-width: 8rem"
              header="Actions"
            >
              <template #body="slotProps">
                <Button
                  v-bind:title="edititem"
                  icon="pi pi-pencil"
                  class="p-button-rounded p-button-success mr-2"
                  @click="editItem(slotProps)"
                />
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
    </div>
    <!-- row -->

    <hr class="mg-t-30 mg-b-30" />

    <div class="row flex-column-reverse flex-lg-row">
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
          :disabled="this.isSaved"
        >
          <i data-feather="save" class="mg-r-5"></i> Save
        </button>
        <button
          type="submit"
          class="btn btn-danger tx-13 btn-uppercase mr-2 mb-2 ml-lg-1 mr-lg-0"
          @click.prevent="submitRequest"
          :disabled="!this.isSaved"
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
</template>
  <script>
import item from "../../components/item/item";
import { h } from "vue";
import Button from "primevue/button";
export default {
  props: ["user"],
  data() {
    return {
      dashboard: this.$env_Url + "/stockrequests/dashboard",
      loading: true,
      items: [],
      errors_exist: false,
      success: false,
      seconds: 0,
      errors: {},
      successMsg: "",
      products: [],
      isSaved: false,
      form: {
        id: 0,
        dept: "",
        date_filed: "",
        time_filed: "",
        date_needed: "",
        cost_code: "",
        remarks: "",
        requested_by: "",
        itemID: "",
        transaction_no: "",
      },
    };
  },
  created() {
    this.fetchPublishedProduct();
    this.loading = false;
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, "0");
    var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
    var yyyy = today.getFullYear();
    var hour = String(today.getHours()).padStart(2, "0");
    var minutes = String(today.getMinutes()).padStart(2, "0");
    var time = hour + ":" + minutes;
    today = mm + "/" + dd + "/" + yyyy;

    this.form.date_filed = today;
    this.form.time_filed = time;
    this.form.requested_by =
      this.user.dept + "_" + this.user.username + "_" + today + " " + time;
    this.form.dept = this.user.dept;
  },
  mounted() {
    this.tempInsert();
  },

  methods: {
    async fetchItems() {
      const res = await this.callApiwParam(
        "post",
        "/requested_items/getRequestedItems",
        this.form
      );
      this.items = res.data;
    },
    async fetchPublishedProduct() {
      const res = await this.getDataFromDB(
        "get",
        "/products/getPublishedProducts"
      );
      this.products = res.data;
    },
    async save() {
      this.errors = {};
      this.errors_exist = false;
      if (this.items.length === 0) {
        this.errors = {
          error: ["Please add atleast 1 item!"],
        };
        this.errors_exist = true;
      } else {
        this.form.date_needed = document.getElementById("date-needed").value;
        this.form.datefiled = document.getElementById("date-filed").value;

        const res = await this.submit(
          "post",
          "/stockrequests/store",
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
          this.form.id = res.data.id;
          this.form.transaction_no = res.data.transaction_no;

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
      if (this.items.length === 0) {
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
          this.success = true;
          this.successMsg = "Request successfully submitted.";
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
          this.fetchItems();
        },
      });
    },
    reset() {
      this.form.date_needed = null;
      this.form.cost_code = "";
      this.form.remarks = "";
    },
    deleteItem(data) {
      let src = data.data.id,
        alt = data.data.id;
      this.form.itemID = alt;

      this.$confirm.require({
        message: "Do you want to delete this item?",
        header: "Delete Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: async () => {
          const res = await this.deleteRecord(
            "post",
            "/requested_items/delete",
            {
              id: data.data.id,
            }
          );
          if (res.status === 200) {
            this.rmessage();
            this.fetchItems();
          } else {
            this.ermessage();
          }
        },
      });
    },
    editItem(data) {
      this.showDialog(data.data);
    },
    async autosave() {
      if (!this.isSaved) {
        this.seconds = this.seconds + 1;
        if (this.seconds == 15) {
          this.form.date_needed = document.getElementById("date-needed").value;
          this.form.datefiled = document.getElementById("date-filed").value;

          const res = await this.submit(
            "post",
            "/stockrequests/autosave",
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
            this.form.id = res.data.id;
            this.form.transaction_no = res.data.transaction_no;
          }

          this.seconds = 0;
        }
      }
    },
    tempInsert: function () {
      setInterval(() => {
        this.autosave();
      }, 1000);
    },
  },
};
</script>
  