<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="w-75">
		<div class="header">
			<span>{{ t('thesistemplater', 'id') }}</span>
			<span>{{ t('thesistemplater', 'First name') }}</span>
			<span>{{ t('thesistemplater', 'Last name') }}</span>
			<span>{{ t('thesistemplater', 'Email') }}</span>
		</div>
		<ul>
			<li v-for="(student, index) in localStudents" :key="index">
				<EditableStudent v-model="localStudents[index]" :index="index" @update:value="updateStudent" />
			</li>
		</ul>
	</div>
</template>
<script>
import EditableStudent from './EditableStudent.vue'
export default {
	name: 'EditableStudentList',
	components: {
		EditableStudent,
	},
	props: {
		value: {
			required: true,
			type: Array,
		},
	},
	data() {
		return {
			localStudents: this.value,
		}
	},
	methods: {
		updateStudent(student) {
			this.$emit('update:value', this.localStudents)
		},
	},
}
</script>
<style scoped>
	.header {
		display: flex;
		justify-content: space-between;
	}
</style>
