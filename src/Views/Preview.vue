<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div id="content" class="app-thesistemplater w-100 h-100">
		<StaticStudentTable v-if="loaded" v-model="students" class="flex-grow" />
		<br>
		<div class="row w-100 control-panel">
			<div>
				<NcButton @click="fixCapitalization">
					{{ t('thesistemplater', 'Fix capitalization') }}
				</NcButton>
			</div>
			<div class="row">
				<div>
					<label for="date" class="label">{{ t('thesistemplater', 'Expiry') }}</label>
					<NcDatetimePicker id="date" v-model="expiry" type="date" />
				</div>
				<div>
					<NcButton class="btn btn-primary" @click="save">
						{{ t('thesistemplater', 'Save') }}
					</NcButton>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import StaticStudentTable from '../Components/StaticStudentTable.vue'

import { NcButton, NcDatetimePicker } from '@nextcloud/vue'
import '@nextcloud/dialogs/style.css'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'

export default {
	name: 'Preview',
	components: {
		StaticStudentTable,
		NcButton,
		NcDatetimePicker,
	},
	props: {
		parsedStudents: {
			type: Array,
			required: true,
		},
	},
	data() {
		return {
			students: [],
			loaded: false,
			expiry: new Date(),
		}
	},
	async mounted() {
		if (this.parsedStudents === undefined || this.parsedStudents === null) {
			this.$router.push({ path: '/' })
		}
		this.parsedStudents.forEach(({ student, valid }) => {
			this.students.push({
				firstName: student.firstName,
				lastName: student.lastName,
				email: student.email,
				type: student.type,
				valid,
			})
		})
		this.loaded = true
	},
	methods: {
		fixCapitalization() {
			for (let i = 0; i < this.students.length; i++) {
				this.students[i].firstName = this.capitalize(this.students[i].firstName)
				this.students[i].lastName = this.capitalize(this.students[i].lastName)
			}
		},
		capitalize(string) {
			return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase()
		},
		async save() {
			const studentsJson = JSON.stringify(this.students)
			const file = new Blob([studentsJson], { type: 'text/json' })
			const formData = new FormData()
			formData.append('file', file, file.name)
			formData.append('expiry', this.expiry.toISOString())
			formData.append('share', this.share)
			try {
				const response = await axios.post(generateUrl('/apps/thesistemplater/api/0.1/student_file/add'), formData)
				if (response.status === 200) {
					showSuccess('Successfully created directories')
				} else {
					showError('Could not upload file: ' + response.statusText)
				}
			} catch (ex) {
				console.error(ex)
				showError('Could not upload file: ' + ex.message)
			}
		},
	},
}
</script>
<style scoped>
	#app-content > div {
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	input[type='text'] {
		width: 100%;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
	}

	.flex-grow {
		flex-grow: 1;
	}

	.w-100 {
		width: 100%;
	}

	.label {
		font-size: large;
	}

	.row {
		display: flex;
		flex-direction: row;
		justify-content: space-between;
	}

	.control-panel {
		margin-top: 0.5em;
	}

	.h-100 {
		height: 100%;
	}

	#content {
		width: 100%;
		display: flex;
		flex-direction: column;
		background-color: rgb(40, 40, 40);
		padding: 1em;
		margin-left: 0 !important;
	}
</style>
