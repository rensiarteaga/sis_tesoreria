<?php
/**
 *@package pXP
 *@file Deposito.php
 *@author  Gonzalo Sarmiento
 *@date 16-05-2016
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.Deposito = Ext.extend(Phx.gridInterfaz, {
        tablaOrigen: 'tes.tproceso_caja',
		idOrigen: 'id_proceso_caja',
		tipo_interfaz : 'fondo_avance',
		
        constructor : function(config) {
            this.maestro = config.maestro;
            Phx.vista.Deposito.superclass.constructor.call(this, config);
            this.iniciarEventos();
			
        },
        Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_libro_bancos'
			},
			type:'Field',
			form:true
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_proceso_caja'
			},
			type:'Field',
			form:true
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_cuenta_bancaria'
			},
			type:'Field',
			form:true
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'tabla'
			},
			type:'Field',
			form:true
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'columna_pk'
			},
			type:'Field',
			form:true
		},
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'columna_pk_valor'
			},
			type:'Field',
			form:true
		},
        {
          config:{
            name: 'id_cuenta_bancaria',
            fieldLabel: 'Cuenta Bancaria',
            allowBlank: false,
            emptyText:'Elija una Cuenta...',
            store:new Ext.data.JsonStore(
              {
              url: '../../sis_tesoreria/control/CuentaBancaria/listarCuentaBancariaUsuario',
              id: 'id_cuenta_bancaria',
              root:'datos',
              sortInfo:{
                field:'id_cuenta_bancaria',
                direction:'ASC'
              },
              totalProperty:'total',
              fields: ['id_cuenta_bancaria','nro_cuenta','nombre_institucion','codigo_moneda','centro','denominacion'],
              remoteSort: true,
              baseParams : {
                par_filtro :'nro_cuenta#denominacion', permiso: 'todos'
              }
              }),
            tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>{nro_cuenta}</b></p><p>Moneda: {codigo_moneda}, {nombre_institucion}</p><p>{denominacion}, Centro: {centro}</p></div></tpl>',
            valueField: 'id_cuenta_bancaria',
            hiddenValue: 'id_cuenta_bancaria',
            displayField: 'nro_cuenta',
            gdisplayField:'desc_cuenta_bancaria',
            listWidth:'280',
            forceSelection:true,
            typeAhead: false,
            triggerAction: 'all',
            lazyRender:true,
            mode:'remote',
            pageSize:20,
            queryDelay:500,
            gwidth: 250,
            anchor: '70%',
            minChars:2,
            renderer:function(value, p, record){return String.format('{0}', record.data['desc_cuenta_bancaria']);}
        },
        type:'ComboBox',
        filters:{pfiltro:'cb.nro_cuenta',type:'string'},
        id_grupo:1,
        grid:true,
        form:true
      },
		{
			config:{
				name: 'fecha',
				fieldLabel: 'Fecha',
				allowBlank: false,
				anchor: '70%',
				gwidth: 90,
				format: 'd/m/Y',
				renderer:function (value,p,record){
					//return value?value.dateFormat('d/m/Y'):''
					if(record.data['sistema_origen']=='FONDOS_AVANCE'){
						return String.format('{0}', '<FONT COLOR="'+record.data['color']+'"><b>'+'F.A. '+value.dateFormat('d/m/Y')+'</b></FONT>');
					}else{
						if(record.data['sistema_origen']=='KERP')
							return String.format('{0}', '<FONT COLOR="'+record.data['color']+'"><b>'+'PG '+value.dateFormat('d/m/Y')+'</b></FONT>');
						else
							return String.format('{0}', '<FONT COLOR="'+record.data['color']+'"><b>'+value.dateFormat('d/m/Y')+'</b></FONT>');
					}
				}
			},
				type:'DateField',
				filters:{pfiltro:'lban.fecha',type:'date'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'observaciones',
				fieldLabel: 'Observaciones',
				allowBlank: true,
				anchor: '70%',
				gwidth: 100,
				maxLength:200
			},
				type:'TextArea',
				filters:{pfiltro:'lban.observaciones',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name:'tipo',
				fieldLabel:'Tipo',
				allowBlank:false,
				emptyText:'Tipo...',
				typeAhead: true,
				triggerAction: 'all',
				disabled:true,
				lazyRender:true,
				mode: 'local',
				valueField: 'estilo',
				gwidth: 80,
				store:new Ext.data.ArrayStore({
                            fields: ['variable', 'valor'],
                            data : [ ['deposito','Depósito']
                                    ]
                                    }),
				valueField: 'variable',
				anchor: '70%',
				displayField: 'valor'
			},
			type:'ComboBox',
			valorInicial:'deposito',
			id_grupo:1,
			filters:{
					 type: 'list',
					  pfiltro:'lban.tipo',
					 options: ['deposito'],
				},
			grid:true,
			form:true
		},
		{
			config:{
				name:'tipo_deposito',
				fieldLabel:'Tipo Deposito',
				allowBlank:false,
				emptyText:'Tipo Deposito...',
				typeAhead: true,
				triggerAction: 'all',
				lazyRender:true,
				mode: 'local',
				valueField: 'estilo',
				gwidth: 80,
				store:new Ext.data.ArrayStore({
                            fields: ['variable', 'valor'],
                            data : [ ['FONDO ROTATIVO','Depósito'],
									['RETENCION','Retención']
                                    ]
                                    }),
				valueField: 'variable',
				anchor: '70%',
				displayField: 'valor'
			},
			type:'ComboBox',
			id_grupo:1,
			grid:false,
			form:true
		},
		{
			config:{
				name: 'nro_deposito',
				fieldLabel: 'Numero Deposito',
				allowBlank: true,
				anchor: '70%',
				gwidth: 125,
				maxLength:50
			},
				type:'TextField',
				filters:{pfiltro:'lban.nro_deposito',type:'string'},
				bottom_filter: true,
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'importe_deposito',
				fieldLabel: 'Importe',
				allowBlank: false,
				anchor: '70%',
				gwidth: 100,
				maxLength:1310722
			},
				type:'NumberField',
				filters:{pfiltro:'lban.importe_deposito',type:'numeric'},
				bottom_filter: true,
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name:'origen',
				fieldLabel:'Origen',
				allowBlank:false,
				emptyText:'Tipo...',
				typeAhead: true,
        		anchor: '70%',
				triggerAction: 'all',
				lazyRender:true,
				mode: 'local',
				valueField: 'estilo',
				gwidth: 60,
				store:['CBB','SRZ','LPB','TJA','SRE','CIJ','TDD','UYU','MIA','MAD']
			},
			type:'ComboBox',
			id_grupo:1,
			filters:{
					 type: 'list',
					  pfiltro:'lban.origen',
					 options: ['CBB','SRZ','TJA','SRE','CIJ','TDD','UYU','MIA','MAD']
				},
			grid:true,
			form:true
		},
		{
			config: {
				name: 'id_libro_bancos_fk',
				fieldLabel: 'Deposito Asociado',
				allowBlank: true,
				emptyText: 'Elija una opción',
				store: new Ext.data.JsonStore({
					url: '../../sis_/control/Clase/Metodo',
					id: 'id_',
					root: 'datos',
					sortInfo: {
						field: 'nombre',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_libro_bancos_fk', 'nombre', 'codigo'],
					remoteSort: true,
					baseParams: {par_filtro: 'movtip.nombre#movtip.codigo'}
				}),
				valueField: 'id_libro_bancos_fk',
				displayField: 'nombre',
				gdisplayField: 'nombre',
				hiddenName: 'id_libro_bancos_fk',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 120,
				minChars: 2,
				renderer : function(value, p, record) {
					return String.format('{0}', record.data['id_libro_bancos_fk']);
				}
			},
			type: 'ComboBox',
			id_grupo: 0,
			filters: {pfiltro: 'movtip.nombre',type: 'string'},
			grid: false,
			form: true
		},
		{
            config:{
                name:'id_finalidad',
                fieldLabel:'Finalidad',
                allowBlank:true,
                emptyText:'Finalidad...',
                store: new Ext.data.JsonStore({
                         url: '../../sis_tesoreria/control/Finalidad/listarFinalidadCuentaBancaria',
                         id: 'id_finalidad',
                         root: 'datos',
                         sortInfo:{
                            field: 'nombre_finalidad',
                            direction: 'ASC'
                    },
                    totalProperty: 'total',
                    fields: ['id_finalidad','nombre_finalidad','color'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'nombre_finalidad'}
                    }),
                valueField: 'id_finalidad',
                displayField: 'nombre_finalidad',
                //tpl:'<tpl for="."><div class="x-combo-list-item"><p><b>{nro_cuenta}</b></p><p>{denominacion}</p></div></tpl>',
                hiddenName: 'id_finalidad',
                forceSelection:true,
                typeAhead: false,
                triggerAction: 'all',
                lazyRender:true,
                mode:'remote',
                pageSize:10,
                queryDelay:1000,
                listWidth:600,
                resizable:true,
                anchor:'70%',
                renderer : function(value, p, record) {
					//return String.format(record.data['nombre_finalidad']);
					return String.format('{0}', '<FONT COLOR="'+record.data['color']+'"><b>'+record.data['nombre_finalidad']+'</b></FONT>');
				}
            },
            type:'ComboBox',
            id_grupo:0,
            grid:true,
            form:true
        }
	],
    title : 'Depositos',
     ActSave:'../../sis_tesoreria/control/ProcesoCaja/insertarCajaDeposito',
	  ActDel:'../../sis_tesoreria/control/ProcesoCaja/eliminarCajaDeposito',
	  ActList:'../../sis_tesoreria/control/ProcesoCaja/listarCajaDeposito',
        id_store : 'id_libro_bancos',
        fields: [
		{name:'id_libro_bancos', type: 'numeric'},
		{name:'id_cuenta_bancaria', type: 'numeric'},
		{name:'desc_cuenta_bancaria', type: 'string'},
		{name:'fecha', type: 'date',dateFormat:'Y-m-d'},
		{name:'a_favor', type: 'string'},
		{name:'id_proceso_wf', type: 'numeric'},
		{name:'id_estado_wf', type: 'numeric'},
		{name:'importe_deposito', type: 'numeric'},
		{name:'origen', type: 'string'},
		{name:'observaciones', type: 'string'},
		{name:'id_libro_bancos_fk', type: 'numeric'},
		{name:'tipo', type: 'string'},
		{name:'nro_deposito', type: 'numeric'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'nombre_finalidad', type: 'string'}
	],
        sortInfo : {
            field : 'fecha',
            direction : 'DESC'
        },
        bdel : true,
        bsave : false,
		bedit : false,
		fheight:'80%',

		loadValoresIniciales:function(){
			Phx.vista.Deposito.superclass.loadValoresIniciales.call(this);
		},
		
		tablaOrigen: 'tes.tproceso_caja',
		idOrigen: 'id_proceso_caja',

		onButtonNew:function(){
			var me = this;
			Phx.vista.Deposito.superclass.onButtonNew.call(this);
			this.cmpFecha.enable();
			this.cmpImporteDeposito.enable();
			this.cmpTabla.setValue(me.tablaOrigen);
			this.cmpColumnaPk.setValue(me.idOrigen);
			this.cmpColumnaPkValor.setValue(me.maestro[me.idOrigen]);
		},

		successSave: function(resp) {
		   Phx.vista.Deposito.superclass.successSave.call(this,resp);
		   Phx.CP.getPagina(this.idContenedorPadre).reload();
		},
		
		

		iniciarEventos:function(){
            var me = this;
			
			this.cmpImporteDeposito = this.getComponente('importe_deposito');
			this.cmpIdLibroBancosFk = this.getComponente('id_libro_bancos_fk');
			this.cmpIdFinalidad = this.getComponente('id_finalidad');
			this.cmpFecha = this.getComponente('fecha');
			this.cmpTabla = this.getComponente('tabla');
			this.cmpColumnaPk = this.getComponente('columna_pk');
			this.cmpColumnaPkValor = this.getComponente('columna_pk_valor');
			this.cmpCuentaBancaria = this.getComponente('id_cuenta_bancaria');
			this.ocultarComponente(this.cmpIdFinalidad);
			this.ocultarComponente(this.cmpIdLibroBancosFk);
			
		},

		onReloadPage:function(m) {
			var me = this;
			this.maestro = m;
			this.Cmp.id_cuenta_bancaria.store.baseParams.id_moneda = this.maestro.id_moneda;					
			this.Cmp.id_cuenta_bancaria.store.baseParams.tipo_interfaz = me.tipo_interfaz;
			this.store.baseParams={tabla : me.tablaOrigen,columna_pk: me.idOrigen,columna_pk_valor : me.maestro[me.idOrigen]};
			this.load({params : {start : 0,limit : me.tam_pag}});
		}
})
</script>