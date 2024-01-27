<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="settings-value-wrapper">
		<label v-if="!inline" for="{{ id }}">{{ text }}</label>
		<NcTextField v-if="!inline"
			:id="id"
			:value.sync="localValue"
			@update:value="updateValue" />
		<NcTextField v-if="inline"
			:id="id"
			:label="text"
			:value.sync="localValue"
			@update:value="updateValue" />
	</div>
</template>
<script>

import { NcTextField } from '@nextcloud/vue'

export default {
	name: 'InputField',
	components: {
		NcTextField,
	},
	props: {
		text: {
			required: true,
			type: String,
		},
		id: {
			required: true,
			type: String,
		},
		value: {
			required: true,
			type: String,
		},
		inline: {
			required: false,
			default: false,
			type: Boolean,
		},
	},
	data() {
		return {
			localValue: this.value,
		}
	},
	watch: {
		value() {
			this.localValue = this.value
		},
	},
	methods: {
		updateValue() {
			this.$emit('update:value', this.localValue)
		},
	},
}
</script>
