<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="inline">
		<InputField :id="'student' + index + '-FirstName'"
			:value.sync="local.firstName"
			:text="t('thesistemplater', 'First name')"
			:inline="true"
			@update:value="updateValue" />
		<InputField :id="'student' + index + '-LastName'"
			:value.sync="local.lastName"
			:text="t('thesistemplater', 'Last name')"
			:inline="true"
			@update:value="updateValue" />
		<InputField :id="'student' + index + '-Email'"
			:value.sync="local.email"
			:text="t('thesistemplater', 'Email')"
			:inline="true"
			@update:value="updateValue" />
		<NcSelect :value.sync="local.specialization"
			:text="t('thesistemplater', 'Specialization')"
			:searchable="false"
			:show-labels="false"
			:close-on-select="true"
			:options="specializationOptions"
			@option:selected="updateValue" />
	</div>
</template>
<script>
import { NcSelect } from '@nextcloud/vue'
import InputField from './InputField.vue'
export default {
	name: 'EditableStudent',
	components: {
		InputField,
		NcSelect,
	},
	props: {
		value: {
			required: true,
			type: Object,
		},
		index: {
			required: true,
			type: Number,
		},
	},
	data() {
		return {
			local: this.value,
			specializationOptions: [
				t('thesistemplater', 'Bachelor'),
				t('thesistemplater', 'Master'),
				t('thesistemplater', 'Doctoral'),
			],
		}
	},
	methods: {
		updateValue(event) {
			this.$emit('update:value', this.local)
		},
	},
}
</script>
<style scoped>
	.inline {
		display: flex;
		justify-content: space-between;
	}
</style>
