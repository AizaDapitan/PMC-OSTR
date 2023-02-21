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
            <li class="breadcrumb-item" aria-current="page">MCD Receiving</li>
            <li class="breadcrumb-item active" aria-current="page">
              Update Request
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Update Stock Request</h4>
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
                disabled="true"
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
                disabled="true"
              />
            </div>
          </div> -->
          <div class="col-lg-6">
            <div class="form-group">
              <label for="origin"
                >Origin</label
              >
              <input
                type="text"
                class="form-control"
                id="origin"
                name="origin"
                v-model="form.origin"
                disabled="true"
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
            disabled="true"
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
            :paginator="true"
            :rows="10"
            stripedRows
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rowsPerPageOptions="[10, 20, 50]"
            responsiveLayout="scroll"
            :loading="loading"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
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
            <Column field="available_qty" header="Available Qty."></Column>
            <Column field="uom" header="UoM"></Column>
            <Column field="description" header="Description"></Column>
            <Column field="requested_qty" header="Requested Qty."></Column>
            <Column field="issued_qty" header="Issued Qty."></Column>
            <Column header="Balance">
              <template #body="slotProps">
                {{
                  slotProps.data.requested_qty - slotProps.data.issued_qty
                }}</template
              ></Column
            >
            <Column
              header="Issuance Qty."
              field="issuance_qty"
              style="color: red"
            >
              <template #editor="{ data, field }">
                <InputText
                  type="number"
                  v-model="data[field]"
                  autofocus
                  min="0"
                />
              </template>
            </Column>

            <Column
              :rowEditor="true"
              style="width: 10%; min-width: 8rem"
              bodyStyle="text-align:center"
            ></Column>
            <Column
              :exportable="false"
              style="min-width: 8rem"
              header="Actions"
            >
              <template #body="slotProps">
                <!-- <Button
                  icon="pi pi-plus"
                  class="p-button-rounded p-button-success mr-2"
                  @click="showDialog(slotProps)"
                /> -->
                <Button
                  icon="pi pi-file"
                  class="p-button-rounded p-button-info mr-2"
                  @click="showHistory(slotProps)"
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
          @click.prevent="saveItem"
        >
          <i data-feather="save" class="mg-r-5"></i> Save
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
import item from "../../components/mcd/item";
import history from "../../components/mcd/issuance_history";
import { h } from "vue";
import Button from "primevue/button";
export default {
  props: ["request"],
  data() {
    return {
      dashboard: this.$env_Url + "/mcds/dashboard",
      loading: true,
      columns: null,
      errors_exist: false,
      errors: {},
      editingRows: [],
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
        origin : this.request.origin,
      },
    };
  },
  created() {
    this.columns = [{ field: "issuance_qty", header: "Issuance Qty." }];
  },
  mounted() {
    this.fetchItems();
    document.getElementById("date-needed").value = this.request.date_needed;
    document.getElementById("date-filed").value = this.request.date_filed;
    if (this.request.time_filed != null) {
      this.form.time_filed = this.request.time_filed.replace(":00.0000000", "");
    }
  },

  methods: {
    async fetchItems() {
      const res = await this.callApiwParam(
        "post",
        "/requested_items/getRequestedItemsSaved",
        this.form
      );
      this.form.items = res.data;

      this.form.items.forEach((obj) => (obj.issuance_qty = ""));
      this.loading = false;
    },
    showDialog(data) {
      const dialogRef = this.$dialog.open(item, {
        props: {
          header: "Issuance",
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
          item_id: data.data.id,
          postedDate: this.request.date_filed,
          // status: "Partial",
          remarks: this.request.remarks,
          item_code: data.data.stock_code,
          ordered_qty: data.data.requested_qty,
          received_by: this.request.requested_by,
          issued_qty: data.data.issued_qty,
        },
        onClose: (options) => {
          this.fetchItems();
        },
      });
    },
    showHistory(data) {
      const dialogRef = this.$dialog.open(history, {
        props: {
          header: "Issuance History",
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
          id: data.data.id,
        },
        onClose: (options) => {
          this.fetchItems();
        },
      });
    },
    onCellEditComplete(event) {
      let { data, newValue, field } = event;
      var error = "";
      var balance = 0;
      if (this.isPositiveInteger(newValue)) {
        data[field] = newValue;
      } else {
        if (newValue.trim().length > 0) {
          error = "Negative Value: Item/s Issuance must be greater than 0!";
          this.singleermessage(error);
          data[field] = "";
          event.preventDefault();
        } else {
          event.preventDefault();
        }
      }
      if (newValue.trim().length > 0) {
        data[field] = newValue;
      } else {
        event.preventDefault();
      }
      if (data["requested_qty"] - data["issued_qty"] < data["issuance_qty"]) {
        balance = data["requested_qty"] - data["issued_qty"];
        error = "Overissue: Current balance is item/s " + balance + " only!";
        this.singleermessage(error);
        data[field] = "";
        event.preventDefault();
      }

      // this.form.items[field] = data[field];
      // console.log(this.form.items);
    },

    isPositiveInteger(val) {
      let str = String(val);
      str = str.trim();
      if (!str) {
        return false;
      }
      str = str.replace(/^0+/, "") || "0";
      var n = Math.floor(Number(str));
      return n !== Infinity && String(n) === str && n >= 0;
    },
    async saveItem() {
      const res = await this.submit("post", "/issuances/store", this.form, {
        headers: {
          "Content-Type":
            "multipart/form-data; charset=utf-8; boundary=" +
            Math.random().toString().substr(2),
        },
      });
      if (res.status === 200) {
        window.location.href = this.$env_Url + "/mcds/dashboard";
      } else {
        this.errors_exist = true;
        this.errors = res.data.errors;
      }
    },
    onRowEditSave(event) {
      let { newData, data, index } = event;
      var error = "";
      var balance = 0;

      balance = newData.requested_qty - newData.issued_qty;
      if (newData.issuance_qty == 0 || newData.issuance_qty.length == 0) {
        newData.issuance_qty = "";
      } else if (balance == 0) {
        error =
          "Fully Issued: Maximum issuance quantity meet!"
        this.singleermessage(error);
        newData.issuance_qty = "";
      } else if (balance < newData.issuance_qty) {
        error =
          "Overissue: Maximum issuance quantity is " +
          balance +
          newData.uom +
          "/s only!";
        this.singleermessage(error);
        newData.issuance_qty = "";
      }
      if (error == "") {
        this.form.items[index] = newData;
      }
    },
  },
};
</script>
<style lang="scss" scoped>
::v-deep(.editable-cells-table td.p-cell-editing) {
  padding-top: 0;
  padding-bottom: 0;
}
</style>
      