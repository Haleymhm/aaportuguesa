$('#QForm').bootstrapValidator({
 
	 message: 'Este valor no es valido',
 
	 feedbackIcons: {
 
		 valid: 'glyphicon glyphicon-ok',
 
		 invalid: 'glyphicon glyphicon-remove',
 
		 validating: 'glyphicon glyphicon-refresh'
 
	 },
 
	 fields: {
 
		rif_empresa: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Rif la Empresa es Requerido'
 
				 }
 
			 }
 
		 },
 
		nombre_empresa: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Nombre de la Empresa es Requerida'
 
				 }
 
			 }
 
		},

		nombre_responsable: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Nombre de del Responsable es Requerida'
 
				 }
 
			 }
 
		},

		nombre: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Nombre es Requerida'
 
				 }
 
			 }
 
		},

		siglas_empresa: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Las Siglas de la Empresa son Requerida'
 
				 }
 
			 }
 
		},

		siglas_unidad: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Las Siglas de la Unidad son Requerida'
 
				 }
 
			 }
 
		},

		nombre_administrador: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Nombre del Administrador es Requerida'
 
				 }
 
			 }
 
		},

		ciudad: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'La Ciudad de la Empresa es Requerida'
 
				 }
 
			 }
 
		},

		telefono_oficina: {
 
			 message: 'El teléfono no es valido',
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El teléfono es requerido y no puede ser vacio'
 
				 },
 
				 regexp: {
 
					 regexp: /^[0-9]+$/,
 
					 message: 'El teléfono solo puede contener números'
 
				 }
 
			 }
 
		},

		telefono_fax: {
 
			 message: 'El teléfono no es valido',
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El teléfono es requerido y no puede ser vacio'
 
				 },
 
				 regexp: {
 
					 regexp: /^[0-9]+$/,
 
					 message: 'El teléfono solo puede contener números'
 
				 }
 
			 }
 
		},

		telefono_movil: {
 
			 message: 'El teléfono no es valido',
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El teléfono es requerido y no puede ser vacio'
 
				 },
 
				 regexp: {
 
					 regexp: /^[0-9]+$/,
 
					 message: 'El teléfono solo puede contener números'
 
				 }
 
			 }
 
		},

		correo_electronico: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Correo Electronico es requerido y no puede ser vacio'
 
				 },
 
				 emailAddress: {
 
					 message: 'El Correo Electronico no es valido'
 
				 }
 
			 }
 
		},

		direccion_empresa: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'La Dirección de la Empresa en Requerida'
 
				 }
 
			 }
 
		},

		direccion: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'La Dirección de la Unidad en Requerida'
 
				 }
 
			 }
 
		},

		send_sms: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Debe Activar o Inactivar Notificacion por Mensaje de Text'
 
				 }
 
			 }
 
		},

		send_email: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Debe Activar o Inactivar Notificacion por Correo Electronico'
 
				 }
 
			 }
 
		},

		sms_nueva_solicitud: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para nueva Solicitud es Requerida'
 
				 }
 
			 }
 
		},

		sms_programar_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Programar Ticket es Requerida'
 
				 }
 
			 }
 
		},

		sms_reprogramar_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Reprogramar el Ticket es Requerida'
 
				 }
 
			 }
 
		},


		sms_escalar_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Escalar el Ticket es Requerida'
 
				 }
 
			 }
 
		},


		sms_completar_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Completar el Ticket es Requerida'
 
				 }
 
			 }
 
		},


		sms_cancelar_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Cancelar el ticket es Requerida'
 
				 }
 
			 }
 
		},


		sms_anular_ticket: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El SMS para Anular el Ticket es Requerida'
 
				 }
 
			 }
 
		},

 		descripcion: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Campo Descripcion es Requerida'
 
				 }
 
			 }
 
		},

		tetramite: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Tipo de Tramite es Requerida'
 
				 }
 
			 }
 
		},

		status: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'El Status es Requerida'
 
				 }
 
			 }
 
		},

		cate_online: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar si quiere Activar/Desactivar la Categoria ONLINE'
 
				 }
 
			 }
 
		},

		horario: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar el Horario de Atencion al Publico'
 
				 }
 
			 }
 
		},

		cargo: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar el Cargo del Responsable'
 
				 }
 
			 }
 
		},

		unidad_online: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar si quiere Activar/Desactivar la Unidad ONLINE'
 
				 }
 
			 }
 
		},

		asunto: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar el Asunto del Punto de Cuenta'
 
				 }
 
			 }
 
		},

		sintesis: {
 
			 validators: {
 
				 notEmpty: {
 
					 message: 'Es Necesario Indicar la Sistesis del Punto de Cuenta'
 
				 }
 
			 }
 
		},
	 }
 
});