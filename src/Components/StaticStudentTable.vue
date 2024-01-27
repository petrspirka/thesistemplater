<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="wrapper">
		<table class="table">
			<thead class="thead">
				<tr>
					<th>{{ t('thesistemplater', 'First name') }}</th>
					<th>{{ t('thesistemplater', 'Last name') }}</th>
					<th>{{ t('thesistemplater', 'Email') }}</th>
					<th>{{ t('thesistemplater', 'Type') }}</th>
					<th />
				</tr>
			</thead>
			<tbody class="tbody">
				<tr v-for="(student, index) in localStudents" :key="index">
					<td>{{ student.firstName }}</td>
					<td>{{ student.lastName }}</td>
					<td>{{ student.email }}</td>
					<td>{{ student.type }}</td>
					<td>
						<NcButton @click="removeStudent(index)">
							{{ t('thesistemplater', 'Remove') }}
						</NcButton>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</template>
<script>
import { NcButton } from '@nextcloud/vue'
export default {
	name: 'StaticStudentTable',
	components: {
		NcButton,
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
		removeStudent(index) {
			this.localStudents.splice(index, 1)
		},
	},
}
</script>
<style scoped>
	.wrapper {
		width: 100%;
		overflow-y: auto;
	}

	.table {
		width: 100%;
		overflow: auto;
		position: relative;
	}

	.thead {
		position: sticky;
		top: 0;
		z-index: 10000;
		background-color: rgb(60, 60, 60);
		padding: 8px;
	}
	.tbody {
		overflow:hidden;
	}
</style>
