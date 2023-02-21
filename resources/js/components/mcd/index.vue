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
              Dashboard
            </li>
          </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">Dashboard</h4>
      </div>
    </div>

    <div class="row row-sm">
      <!-- Start Pages -->

      <div class="col-md-12">
        <Toolbar class="mb-4 table-light">
          <template #start>
            <Button
              label="Export"
              icon="pi pi-upload"
              class="p-button-help p-button-sm mr-2"
              @click="exportCSV($event)"
            />
            <div class="search-form mg-r-10" style="width: 200px">
              <Calendar
                id="icon"
                v-model="form.dateFrom"
                :showIcon="true"
                v-tooltip="'Date From'"
                pattern="dd/MM/yyyy"
                autocomplete="off"
              />
            </div>
            <div class="search-form mg-r-10" style="width: 200px">
              <Calendar
                id="icon2"
                v-model="form.dateTo"
                :showIcon="true"
                v-tooltip="'Date To'"
              />
            </div>

            <Button
              icon="pi pi-search"
              class="p-button-success p-button-sm mr-2"
              v-tooltip="'Search'"
              @click="fetchRecord()"
            />
          </template>
          <template #end>
            <div class="search-form mg-r-10">
              <input
                name="search"
                type="search"
                id="search"
                class="form-control"
                placeholder="Search"
                v-model="filters['global'].value"
              />
              <button class="btn filter" id="btnSearch">
                <i data-feather="search"></i>
              </button>
            </div>
          </template>
        </Toolbar>
        <div class="table-list mg-b-10">
          <div class="table-responsive-lg">
            <DataTable
              ref="dt"
              :value="requests"
              :paginator="true"
              :rows="10"
              stripedRows
              removableSort
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              :rowsPerPageOptions="[10, 20, 50]"
              responsiveLayout="scroll"
              :loading="loading"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              v-model:filters="filters"
              filterDisplay="menu"
              :globalFilterFields="[
                'transaction_no',
                'dept',
                'date_filed',
                'time_filed',
                'date_needed',
                'status',
                'cost_code',
              ]"
            >
              <template #empty> No record found. </template>
              <template #loading> Loading data. Please wait. </template>
              <Column field="id" hidden="true"></Column>
              <Column
                field="transaction_no"
                header="Transaction #"
                :sortable="true"
                style="min-width: 11rem"
              ></Column>
              <!-- <Column
                field="cost_code"
                header="Cost Code"
                :sortable="true"
                style="min-width: 10rem"
              ></Column> -->
              <Column
                field="date_filed"
                header="Date Requested"
                :sortable="true"
                style="min-width: 9rem"
              >
              </Column>
              <Column
                field="time_filed"
                header="Time Requested"
                :sortable="true"
                style="min-width: 4rem"
              >
                <template #body="slotProps">
                  <span>{{
                    slotProps.data.time_filed.replace(":00.0000000", "")
                  }}</span>
                </template></Column
              >
              <Column
                field="dept"
                header="Department"
                :sortable="true"
                style="min-width: 10rem"
              ></Column>
              <Column
                field="date_needed"
                header="Date Needed"
                :sortable="true"
              ></Column>
              <Column field="status" header="Status" :sortable="true">
                <template #body="slotProps">
                  <span
                    v-if="
                      slotProps.data.status.toLowerCase() == 'fully approved' &&
                      !slotProps.data.isReceived
                    "
                    class="badge badge-warning tx-uppercase"
                    >Approved</span
                  >
                  <span
                    v-else-if="
                      slotProps.data.status.toLowerCase() == 'cancelled' &&
                      !slotProps.data.isReceived
                    "
                    class="badge badge-danger tx-uppercase"
                    >Cancelled</span
                  >
                  <span
                    v-else-if="slotProps.data.isReceived"
                    class="badge badge-info tx-uppercase"
                    >Received</span
                  >
                  <span
                    v-else-if="
                      slotProps.data.status.toLowerCase() == 'completed' &&
                      !slotProps.data.isReceived
                    "
                    class="badge badge-success tx-uppercase"
                    >Completed</span
                  >
                  <span v-else class="badge tx-uppercase">Pending</span>
                </template>
              </Column>
              <Column
                :exportable="false"
                style="min-width: 15rem"
                header="Actions"
              >
                <template #body="slotProps">
                  <Button
                    v-bind:title="viewMsg"
                    icon="pi pi-eye"
                    class="p-button-rounded p-button-success mr-2"
                    @click="view(slotProps)"
                  />
                  <Button
                    v-bind:title="receiveMsg"
                    icon="pi pi-arrow-circle-down"
                    class="p-button-rounded p-button-warning mr-2"
                    @click="receive(slotProps)"
                    :disabled="slotProps.data.isReceived"
                  />
                  <Button
                    v-bind:title="editMsg"
                    icon="pi pi-pencil"
                    class="p-button-rounded p-button-warning mr-2"
                    @click="edit(slotProps)"
                    :disabled="!slotProps.data.isReceived"
                  />
                </template>
              </Column>
            </DataTable>
          </div>
        </div>
      </div>
      <!-- End Pages -->
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
  <ConfirmDialog></ConfirmDialog>
</template>
      <script>
import { FilterMatchMode, FilterOperator } from "primevue/api";
export default {
  data() {
    return {
      requests: [],
      filters: null,
      viewMsg: "View Stock Transfer Request",
      receiveMsg: "Receive Stock Transfer Request",
      editMsg: "Update Stock Transfer Request",
      loading: true,
      form: {
        id: 0,
        dateFrom: new Date(),
        dateTo: new Date(),
      },
    };
  },
  created() {
    const today = new Date();
    this.form.dateFrom = new Date(today.getFullYear(),today.getMonth(),1);
    this.form.dateTo =  new Date(today.getFullYear(),today.getMonth() + 1,0);
    this.fetchRecord();
    this.initFilters();
  },
  methods: {
    async fetchRecord() {
      this.form.dateFrom = this.convert(this.form.dateFrom);
      this.form.dateTo = this.convert(this.form.dateTo);
      const res = await this.callApiwParam(
        "post",
        "/mcds/getRequests",
        this.form
      );
      // this.requests = res.data;
      this.requests = res.data.filter(function(el){ return el.completed == false;})
      this.loading = false;
    },
    convert(string) {
        return new Date(string)
          .toLocaleString("en-us", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
          })
          .replace(/(\d+)\/(\d+)\/(\d+)/, "$3-$1-$2");
      },
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
    clearFilter() {
      this.filters["global"].value = null;
    },
    exportCSV() {
      this.$refs.dt.exportCSV();
    },

    view(data) {
      let src = data.data.id,
        alt = data.data.id;
      window.location.href = this.$env_Url + "/mcds/view/" + alt;
    },
    edit(data) {
      let src = data.data.id,
        alt = data.data.id;
      window.location.href = this.$env_Url + "/mcds/edit/" + alt;
    },
    receive(data) {
      let src = data.data.id,
        alt = data.data.id;
      this.form.id = alt;

      this.$confirm.require({
        message: "Do you want to receive this request?",
        header: "Receive Confirmation",
        icon: "pi pi-info-circle",
        acceptClass: "p-button-danger",
        accept: async () => {
          const res = await this.submit("post", "/mcds/receive", this.form);
          if (res.status === 200) {
            this.recmessage();
            this.fetchRecord();
          } else {
            this.ermessage();
          }
        },
      });
    },
  },
};
</script>