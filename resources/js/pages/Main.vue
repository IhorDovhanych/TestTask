<template>
    <div class="w-full mt-40 flex justify-center align-items-center">
        <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8">
            <form class="space-y-6" @submit="submitForm">
                <h5 class="text-xl font-medium text-gray-900">Delivery propositions</h5>
                <div>
                    <div v-if="deliveryCompanies != null">
                        <label for="deliveryCompany" class="block mb-2 text-sm font-medium text-gray-900">
                            Select an option
                        </label>
                        <select id="deliveryCompany" v-model="selectedOption"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                        >
                            <option value="" selected hidden>Select an option</option>
                            <option v-for="el in deliveryCompanies" :value="el">ID: {{el.id}} {{ el.name }}</option>
                        </select>
                    </div>
                </div>
                <div v-if="selectedOption != null">
                    <div class="mb-3">
                        {{ selectedOption.name }}
                    </div>
                    <div v-for="el in selectedOption.propositions" class="bg-blue-100 p-3 rounded mb-1">
                        <div v-if="el.type !== 'fixed'">
                            If the parcel is
                            <span v-if="el.min !== null && el.min !== 0">
                                more than {{el.min}} Kg
                            </span>
                            <span v-if="el.min !== null && el.max !== null && el.min !== 0">
                                and
                            </span>
                            <span v-if="el.max !== null">
                                less than {{el.max}} Kg
                            </span>
                            <span v-if="el.type !== 'ranged'">
                                then the price is: {{ el.price }} EUR
                            </span>
                            <span v-else-if="el.type !== 'range-fixed'">
                                then the price per kilo is: {{ el.price }} EUR
                            </span>
                        </div>
                        <div v-else-if="el.type === 'fixed'">
                            The price per kilo is: {{ el.price }} EUR
                        </div>
                    </div>
                </div>
                <div>
                    <label for="kilo" class="block mb-2 text-sm font-medium text-gray-900">Your parcel weight (Kg)</label>
                    <input @input="handleInput()" type="number" v-model="usersKilo" name="kilo" id="kilo" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="10 Kg" required>
                </div>
                <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Calculate price
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            deliveryCompanies: null,
            selectedOption: "",
            usersKilo: null,
            calculatedPrice: null
        };
    },
    mounted() {
        axios
            .get('http://127.0.0.1:8000/api/delivery-companies')
            .then(response => (this.deliveryCompanies = response.data.data[0]));
    },
    methods: {
        submitForm(event) {
            event.preventDefault();
            const companyId = this.selectedOption.id
            axios.get(`http://127.0.0.1:8000/api/delivery-company/${companyId}/price/${this.usersKilo}`, {
            })
                .then(response => {
                    this.calculatedPrice = response.data;
                    alert(`${this.calculatedPrice} EUR`)
                })
                .catch(error => {
                    console.error(error);
                });
        },
        handleInput() {
            if (this.usersKilo <= 0) {
                this.usersKilo = null;
            }
        }
    }
};
</script>

<style scoped>
</style>
