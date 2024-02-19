<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop is-8-widescreen is-10-tablet">
                    <div class="box">

                        <div class="box-header">
                            SALES
                        </div>
                       <b-field label="Filters"></b-field>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Page" label-position="on-border">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                    </b-select>
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
                                    </b-select>
                                </b-field>
                            </div>

                            <div class="column">
                                <b-field label="Date Filter" label-position="on-border" expanded>
                                    <b-datepicker v-model="search.start_date" placeholder="Start date" expanded></b-datepicker>
                                    <b-datepicker v-model="search.end_date" placeholder="End date" expanded></b-datepicker>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Item Name" expanded label-position="on-border">
                                    <b-input type="text" expanded
                                        v-model="search.item_name" placeholder="Search Item Name"
                                        @keyup.native.enter="loadAsyncData"/>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Customer Name" expanded label-position="on-border">
                                    <b-input type="text" expanded
                                        v-model="search.customer" placeholder="Search Customer"
                                        @keyup.native.enter="loadAsyncData"/>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column">
                                <b-field label="Barcode" label-position="on-border">
                                    <b-input type="text" expanded
                                        v-model="search.barcode" placeholder="Search Barcode"
                                        @keyup.native.enter="loadAsyncData"/>
                                </b-field>
                            </div>

                            <div class="column">
                                <b-field label="Serial" label-position="on-border">
                                    <b-input type="text" expanded
                                        v-model="search.serial" placeholder="Search Serial"
                                        @keyup.native.enter="loadAsyncData"/>
                                </b-field>
                            </div>
                        </div>

                        <div class="buttons">
                            <b-button type="is-primary" 
                                icon-right="magnify"
                                label="Search"
                                @click="loadAsyncData"/>

                            <b-button type="is-info"
                                class="is-outlined" 
                                icon-right="printer"
                                label="Print Preview"
                                @click="printPreview"/>
                        </div>

                        <hr>

                        <b-table
                            :data="data"
                            :loading="loading"
                            paginated
                            detailed
                            backend-pagination
                            :total="total"
                            :per-page="perPage"
                            @page-change="onPageChange"
                            aria-next-label="Next page"
                            aria-previous-label="Previous page"
                            aria-page-label="Page"
                            aria-current-label="Current page"
                            backend-sorting
                            :default-sort-direction="defaultSortDirection"
                            @sort="onSort">

                            <b-table-column field="sales_id" label="ID" v-slot="props">
                                {{ props.row.sales_id }}
                            </b-table-column>
                            <b-table-column field="sales_date" label="Sales Date" v-slot="props">
                                {{ props.row.sales_date | formatDateTime }}
                            </b-table-column>
                            <b-table-column field="sales" label="Sales" v-slot="props">
                                {{ props.row.total_sales | formatToCurrency }}
                            </b-table-column>
                            <b-table-column field="customer" label="Customer" v-slot="props">
                                {{ props.row.customer }}
                            </b-table-column>

                            <b-table-column field="sys_user" label="User" v-slot="props">
                                {{ props.row.user.lname }}, {{ props.row.user.fname }}
                            </b-table-column>

                            <!-- <b-table-column label="Action" v-slot="props">
                                <div class="is-flex">
                                   <b-tooltip label="Edit" type="is-warning">
                                       <b-button class="button is-small is-warning mr-1" tag="a" icon-right="pencil" @click="edit(props.row.stock_in_id)"></b-button>
                                    </b-tooltip>
                                    <b-tooltip label="Delete" type="is-danger">
                                        <b-button class="button is-small is-danger mr-1" icon-right="delete" @click="confirmDelete(props.row.stock_in_id)"></b-button>
                                    </b-tooltip>
                                </div>
                            </b-table-column> -->

                            <template slot="detail" slot-scope="props">

                                <table class="is-fullwidth">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Remarks</th>
                                        <th>Serial</th>
                                    </tr>
                                    <tr v-for="item in props.row.sales_details" :key="item.sales_detail_id">
                                        <td>{{ item.item_name }}
                                        (   
                                            <span v-for="serial in item.serials" :key="serial.sales_item_serial_id">
                                                {{ serial.serial}}, 
                                            </span>
                                        )</td>
                                        <td class="has-text-centered">{{ item.qty }}</td>
                                        <td class="has-text-centered">{{ item.price | formatToCurrency }}</td>
                                        <td class="has-text-centered text-container">{{ item.remarks }}</td>
                                        <td class="has-text-centered">
                                            <span v-for="(item, index) in item.serials" :key="`serial${index}`">{{ item.serial }}, </span>
                                        </td>

                                    </tr>
                                </table>

                                
                            </template>
                            
                        </b-table>

                        <hr>

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'sales_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',

            search: {
                item_name: '',
                barcode: '',
                serial: '',
                start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0),
                customer: '',
            },

            isModalCreate: false,

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },
        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `start=${this.$formatDate(this.search.start_date)}`,
                `end=${this.$formatDate(this.search.end_date)}`,
                `itemname=${this.search.item_name}`,
                `customer=${this.search.customer}`,
                `serial=${this.search.serial}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-sales?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },



        //alert box ask for deletion
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/stock-in/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        printPreview(){

        },

        clearFields(){
            this.fields = {
                item_id : 0,
                qty_in: 0,
                price: 0,
                stock_in_date: new Date(),
            };
        },
        edit: function(id){
            //window.location = '/stock-in/' + id + '/edit'
        }



    },

    mounted() {
        this.loadAsyncData();
    }
}
</script>


<style scoped>
    .text-container {
        max-width: 200px; /* Adjust the maximum width as needed */
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        }

</style>
