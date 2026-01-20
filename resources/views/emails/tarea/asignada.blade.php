<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva tarea asignada</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f3f6; font-family: Arial, Helvetica, sans-serif; color:#1f2937;">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
    <tr>
        <td align="center">

            <!-- Contenedor principal -->
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="max-width:600px; background:#ffffff; border:1px solid #e5e7eb;">

                <!-- Header -->
                <tr>
                    <td style="padding:20px 28px; border-bottom:1px solid #e5e7eb;">
                        <h2 style="margin:0; font-size:18px; font-weight:600;">
                            Asignación de tarea
                        </h2>
                    </td>
                </tr>

                <!-- Cuerpo -->
                <tr>
                    <td style="padding:24px 28px; font-size:14px; line-height:1.6;">
                        <p style="margin:0 0 14px 0;">
                            Estimado/a <strong>{{ $user->name }}</strong>,
                        </p>

                        <p style="margin:0 0 18px 0;">
                            Se le ha asignado una nueva tarea.
                        </p>

                        <!-- Información -->
                        <table width="100%" cellpadding="0" cellspacing="0"
                               style="background:#f9fafb; border:1px solid #e5e7eb;">

                            <tr>
                                <td style="padding:12px 16px;">
                                    <strong>Proyecto:</strong>
                                </td>
                                <td style="padding:12px 16px;">
                                    {{ $tarea->proyecto->nombre }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    <strong>Tarea:</strong>
                                </td>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    {{ $tarea->titulo }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    <strong>Prioridad:</strong>
                                </td>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    {{ ucfirst($tarea->prioridad ?? 'Normal') }}
                                </td>
                            </tr>

                            <tr>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    <strong>Fecha límite:</strong>
                                </td>
                                <td style="padding:12px 16px; border-top:1px solid #e5e7eb;">
                                    {{ $tarea->fecha_limite ?? 'No definida' }}
                                </td>
                            </tr>
                        </table>

                        <!-- CTA -->
                        <div style="margin-top:24px;">
                            <a href="{{ url('/proyectos/'.$tarea->proyecto_id) }}"
                               style="display:inline-block; padding:10px 18px; background:#e06d2a; color:#ffffff; text-decoration:none; font-size:13px; font-weight:600;">
                                Acceder a la tarea
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="padding:18px 28px; border-top:1px solid #e5e7eb; font-size:12px; color:#6b7280;">
                        Este es un mensaje automático del sistema.  
                        Por favor, no responda a este correo.
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
