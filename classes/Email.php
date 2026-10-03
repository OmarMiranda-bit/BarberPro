<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{

    public $nombre;
    public $email;
    public $token;
    public $folio;

    public function __construct($nombre, $email, $token, $folio = '')
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        $this->folio = $folio;
    }

    public function enviarConfirmacion()
    {
        // Crear el objeto de email
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@barberpro.com', 'BarberPRO');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu cuenta en BarberPRO';

        // Si se utiliza html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #121212; margin: 0; padding: 20px; color: #e0e0e0; }';
        $contenido .= '.card { max-width: 500px; margin: 0 auto; background-color: #1e1e1e; border: 1px solid #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }';
        $contenido .= '.brand { color: #c5a059; font-size: 22px; font-weight: bold; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 2px; }';
        $contenido .= '.title { color: #ffffff; font-size: 18px; font-weight: bold; margin-bottom: 12px; }';
        $contenido .= '.text { font-size: 14px; color: #a1a1aa; line-height: 1.6; margin-bottom: 25px; }';
        $contenido .= '.btn { display: inline-block; background-color: #c5a059; color: #121212; font-weight: bold; font-size: 14px; padding: 12px 28px; text-decoration: none; border-radius: 6px; text-transform: uppercase; letter-spacing: 1px; }';
        $contenido .= '.footer { font-size: 12px; color: #71717a; margin-top: 30px; border-top: 1px solid #2a2a2a; padding-top: 15px; }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="card">';
        $contenido .= '  <div class="brand">BarberPRO</div>';
        $contenido .= '  <div class="title">¡Bienvenido, ' . htmlspecialchars($this->nombre) . '!</div>';
        $contenido .= '  <p class="text">Has creado tu cuenta con éxito. Estás a un solo paso de agendar tus citas; confirma tu cuenta haciendo clic en el siguiente botón:</p>';
        $contenido .= '  <a href="' . $_ENV['HOST'] . '/confirmar-cuenta?token=' . $this->token . '" class="btn">Confirmar Cuenta</a>';
        $contenido .= '  <p class="footer">Si tú no creaste esta cuenta, puedes ignorar este mensaje de forma segura.</p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        // Para enviar el email 
        return $mail->send();
    }
    //Para enviar el email para reestablecer el password
    public function enviarInstrucciones()
    {
        // Crear el objeto de email
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@barberpro.com', 'BarberPRO');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Restablece tu contraseña en BarberPRO';

        // Si se utiliza html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #121212; margin: 0; padding: 20px; color: #e0e0e0; }';
        $contenido .= '.card { max-width: 500px; margin: 0 auto; background-color: #1e1e1e; border: 1px solid #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }';
        $contenido .= '.brand { color: #c5a059; font-size: 22px; font-weight: bold; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 2px; }';
        $contenido .= '.title { color: #ffffff; font-size: 18px; font-weight: bold; margin-bottom: 12px; }';
        $contenido .= '.text { font-size: 14px; color: #a1a1aa; line-height: 1.6; margin-bottom: 25px; }';
        $contenido .= '.btn { display: inline-block; background-color: #c5a059; color: #121212; font-weight: bold; font-size: 14px; padding: 12px 28px; text-decoration: none; border-radius: 6px; text-transform: uppercase; letter-spacing: 1px; }';
        $contenido .= '.footer { font-size: 12px; color: #71717a; margin-top: 30px; border-top: 1px solid #2a2a2a; padding-top: 15px; }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="card">';
        $contenido .= '  <div class="brand">BarberPRO</div>';
        $contenido .= '  <div class="title">Restablecer Contraseña</div>';
        $contenido .= '  <p class="text">Hola <strong>' . htmlspecialchars($this->nombre) . '</strong>, has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para crear una nueva:</p>';
        $contenido .= '  <a href="' . $_ENV['HOST'] . '/recuperar?token=' . $this->token . '" class="btn">Restablecer Contraseña</a>';
        $contenido .= '  <p class="footer">Si tú no solicitaste este cambio, puedes ignorar este mensaje de forma segura.</p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        // Para enviar el email 
        return $mail->send();
    }
    // Para enviar detalles de la cita agendada al correo del cliente, incluyendo el folio y un enlace directo a la página de detalles de la cita 
    public function enviarDetalles()
    {
        $mail = new PHPMailer();
        $mail->SMTPDebug = 0; // 0 = apagado (importante para no ensuciar la respuesta JSON)
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('citas@barberpro.com', 'BarberPRO');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Detalles de tu Cita - BarberPRO';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // ENLACE DIRECTO - Incluye folio y email
        $urlDetalle = $_ENV['HOST'] . "/reservar/cita/detalle?folio=" .
            urlencode($this->folio) . "&email=" . urlencode($this->email);

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #121212; margin: 0; padding: 20px; color: #e0e0e0; }';
        $contenido .= '.card { max-width: 520px; margin: 0 auto; background-color: #1e1e1e; border: 1px solid #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }';
        $contenido .= '.brand { color: #c5a059; font-size: 22px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 2px; }';
        $contenido .= '.subtitle { color: #a1a1aa; font-size: 13px; margin-bottom: 20px; }';
        $contenido .= '.title { color: #ffffff; font-size: 18px; font-weight: bold; margin-bottom: 15px; }';
        $contenido .= '.folio-box { background-color: #18181b; border: 1px dashed #c5a059; padding: 15px; border-radius: 8px; margin: 20px 0; }';
        $contenido .= '.folio-label { color: #a1a1aa; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }';
        $contenido .= '.folio-value { color: #c5a059; font-size: 24px; font-weight: bold; letter-spacing: 2px; margin-top: 4px; }';
        $contenido .= '.text { font-size: 14px; color: #a1a1aa; line-height: 1.6; margin-bottom: 25px; }';
        $contenido .= '.btn { display: inline-block; background-color: #c5a059; color: #121212; font-weight: bold; font-size: 14px; padding: 12px 28px; text-decoration: none; border-radius: 6px; text-transform: uppercase; letter-spacing: 1px; }';
        $contenido .= '.footer { font-size: 12px; color: #71717a; margin-top: 30px; border-top: 1px solid #2a2a2a; padding-top: 15px; }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="card">';
        $contenido .= '  <div class="brand">BarberPRO</div>';
        $contenido .= '  <div class="subtitle">Confirmación de Reserva</div>';
        $contenido .= '  <div class="title">¡Hola, ' . htmlspecialchars($this->nombre) . '!</div>';
        $contenido .= '  <p class="text">Tu cita ha sido confirmada exitosamente. Guarda tu código de seguimiento para futuras consultas:</p>';
        $contenido .= '  <div class="folio-box">';
        $contenido .= '    <div class="folio-label">Folio de Cita</div>';
        $contenido .= '    <div class="folio-value">' . htmlspecialchars($this->folio) . '</div>';
        $contenido .= '  </div>';
        $contenido .= '  <a href="' . $urlDetalle . '" class="btn">Ver Detalles de mi Cita</a>';
        $contenido .= '  <p class="footer">Si necesitas consultar los detalles, cancelar o reagendar tu cita, presiona el botón de arriba.</p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;
        return $mail->send();
    }

    // Correos para enviar el email al agregar barberos nuevos 
    public function enviarConfirmacionBarberos()
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@barberpro.com', 'BarberPRO');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Bienvenido al equipo - Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #121212; margin: 0; padding: 20px; color: #e0e0e0; }';
        $contenido .= '.card { max-width: 500px; margin: 0 auto; background-color: #1e1e1e; border: 1px solid #2a2a2a; border-radius: 12px; padding: 30px; text-align: center; box-shadow: 0 8px 20px rgba(0,0,0,0.4); }';
        $contenido .= '.brand { color: #c5a059; font-size: 22px; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 2px; }';
        $contenido .= '.badge { display: inline-block; background-color: rgba(197, 160, 89, 0.15); color: #c5a059; border: 1px solid #c5a059; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }';
        $contenido .= '.title { color: #ffffff; font-size: 18px; font-weight: bold; margin-bottom: 12px; }';
        $contenido .= '.text { font-size: 14px; color: #a1a1aa; line-height: 1.6; margin-bottom: 25px; }';
        $contenido .= '.btn { display: inline-block; background-color: #c5a059; color: #121212; font-weight: bold; font-size: 14px; padding: 12px 28px; text-decoration: none; border-radius: 6px; text-transform: uppercase; letter-spacing: 1px; }';
        $contenido .= '.footer { font-size: 12px; color: #71717a; margin-top: 30px; border-top: 1px solid #2a2a2a; padding-top: 15px; }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="card">';
        $contenido .= '  <div class="brand">BarberPRO</div>';
        $contenido .= '  <div><span class="badge">Nuevo Barbero</span></div>';
        $contenido .= '  <div class="title">¡Bienvenido, ' . htmlspecialchars($this->nombre) . '!</div>';
        $contenido .= '  <p class="text">Has sido registrado como barbero en BarberPRO. Para activar tu acceso y comenzar a gestionar tus citas, confirma tu cuenta presionando el siguiente botón:</p>';
        $contenido .= '  <a href="' . $_ENV['HOST'] . '/confirmar-cuenta?token=' . $this->token . '" class="btn">Confirmar Cuenta</a>';
        $contenido .= '  <p class="footer">Si tú no solicitaste este acceso, comunícate con el administrador del sistema.</p>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;
        return $mail->send();
    }

    public function enviarContacto($telefono, $asunto, $mensaje)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        // Remitente (datos de la persona que llena el formulario)
        $mail->setFrom($this->email, $this->nombre);

        // Destinatario (Correo de la barbería)
        $mail->addAddress('contacto@barberpro.com', 'BarberPRO Admin');

        $mail->Subject = 'Nuevo Mensaje de Contacto: ' . $asunto;

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // $contenido = '<html>';
        // $contenido .= '<p><strong>Nombre:</strong> ' . $this->nombre . '</p>';
        // $contenido .= '<p><strong>Email:</strong> ' . $this->email . '</p>';
        // $contenido .= '<p><strong>Teléfono:</strong> ' . ($telefono ?: 'No proporcionado') . '</p>';
        // $contenido .= '<p><strong>Asunto:</strong> ' . $asunto . '</p>';
        // $contenido .= '<p><strong>Mensaje:</strong></p>';
        // $contenido .= '<p>' . nl2br($mensaje) . '</p>';
        // $contenido .= '</html>';
        $contenido = '<html>';
        $contenido .= '<head>';
        $contenido .= '<style>';
        $contenido .= 'body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #121212; margin: 0; padding: 20px; color: #e0e0e0; }';
        $contenido .= '.card { max-width: 580px; margin: 0 auto; background-color: #1e1e1e; border: 1px solid #2a2a2a; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }';
        $contenido .= '.header { background-color: #18181b; padding: 25px; text-align: center; border-bottom: 2px solid #c5a059; }';
        $contenido .= '.header h1 { margin: 0; color: #c5a059; font-size: 24px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }';
        $contenido .= '.header p { margin: 5px 0 0 0; color: #a1a1aa; font-size: 13px; }';
        $contenido .= '.body { padding: 30px; }';
        $contenido .= '.grid { display: table; width: 100%; margin-bottom: 20px; }';
        $contenido .= '.row { display: table-row; }';
        $contenido .= '.field { padding: 12px 0; border-bottom: 1px solid #2a2a2a; }';
        $contenido .= '.label { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #c5a059; font-weight: bold; margin-bottom: 4px; }';
        $contenido .= '.value { font-size: 15px; color: #ffffff; }';
        $contenido .= '.message-container { margin-top: 20px; background-color: #18181b; border-left: 4px solid #c5a059; padding: 18px; border-radius: 4px; }';
        $contenido .= '.message-title { font-size: 12px; text-transform: uppercase; color: #a1a1aa; font-weight: bold; margin-bottom: 8px; }';
        $contenido .= '.message-text { font-size: 14px; line-height: 1.6; color: #d4d4d8; margin: 0; }';
        $contenido .= '.footer { background-color: #141414; padding: 15px; text-align: center; font-size: 12px; color: #71717a; border-top: 1px solid #2a2a2a; }';
        $contenido .= '</style>';
        $contenido .= '</head>';
        $contenido .= '<body>';
        $contenido .= '<div class="card">';
        $contenido .= '  <div class="header">';
        $contenido .= '    <h1>BarberPRO</h1>';
        $contenido .= '    <p>Solicitud de contacto desde el sitio web</p>';
        $contenido .= '  </div>';
        $contenido .= '  <div class="body">';
        $contenido .= '    <div class="field">';
        $contenido .= '      <div class="label">Cliente</div>';
        $contenido .= '      <div class="value">' . htmlspecialchars($this->nombre) . '</div>';
        $contenido .= '    </div>';
        $contenido .= '    <div class="field">';
        $contenido .= '      <div class="label">Correo Electrónico</div>';
        $contenido .= '      <div class="value">' . htmlspecialchars($this->email) . '</div>';
        $contenido .= '    </div>';
        $contenido .= '    <div class="field">';
        $contenido .= '      <div class="label">Teléfono</div>';
        $contenido .= '      <div class="value">' . htmlspecialchars($telefono ?: 'No proporcionado') . '</div>';
        $contenido .= '    </div>';
        $contenido .= '    <div class="field">';
        $contenido .= '      <div class="label">Asunto</div>';
        $contenido .= '      <div class="value" style="text-transform: capitalize;">' . htmlspecialchars($asunto) . '</div>';
        $contenido .= '    </div>';
        $contenido .= '    <div class="message-container">';
        $contenido .= '      <div class="message-title">Mensaje recibido:</div>';
        $contenido .= '      <p class="message-text">' . nl2br(htmlspecialchars($mensaje)) . '</p>';
        $contenido .= '    </div>';
        $contenido .= '  </div>';
        $contenido .= '  <div class="footer">';
        $contenido .= '    Mensaje automático enviado desde BarberPRO • ' . date('d/m/Y H:i');
        $contenido .= '  </div>';
        $contenido .= '</div>';
        $contenido .= '</body>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        // Retornar directamente el envío
        return $mail->send();
    }
}
