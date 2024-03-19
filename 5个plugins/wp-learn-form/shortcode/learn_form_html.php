<div class="form-container">
    <?php $verification_code = generate_verification_code();

    // Generate the verification code image
$verification_image_url = generate_verification_image($verification_code); ?>

        <div class="form-title">
            <h1 style="display:inline-block;margin-right: 5rem;" class="form-title">联系我们 </h1><span class="warning-info" style="display:none">111</span>
        </div>
        

        <form action="#" method="post" name="wlf_form" id="wlf_form" style="width: 100%;margin-top:3rem">
            <?php wp_nonce_field( 'wlf', 'wlf_form' ); ?>
            <input type="hidden" id="generated-code" value="<script>document.write(verificationCode)</script>">
            <label for="wlf_name"><?php  _e( '姓名', 'wlform' ); ?></label>
            <input type="text" id="wlf_name" name="wlf_name" class="form-input"  required>

            <label for="wlf_email"><?php  _e( '邮箱', 'wlform' ); ?></label>
            <input type="email" id="wlf_email" name="wlf_email" class="form-input"  required>

            <label for="wlf_phone"><?php  _e( '电话号码', 'wlform' ); ?></label>
            <input type="phone" id="wlf_phone" name="wlf_phone" class="form-input" pattern="^(13[0-9]|14[5-9]|15[0-3,5-9]|166|17[0-8]|18[0-9]|19[8-9])\d{8}$" title="请输入正确的手机号码" required>

            <label for="wlf_title"><?php  _e( '留言标题', 'wlform' ); ?></label>
            <input type="title" id="wlf_title" name="wlf_title" class="form-input"  required>

            <label for="message"><?php  _e( '留言内容', 'wlform' ); ?></label>
            <textarea id="message" name="message" class="form-textarea" required></textarea>

            <label for="user_verification_code">请输入验证码:</label>
            <img src="<?php echo $verification_image_url; ?>" alt="Verification Code">
            <input type="hidden" name="actual_verification_code" value="<?php echo $verification_code; ?>">
            <input type="number" name="user_verification_code" required>
            <br>
            <br>

            <div style="display: block;">
                <button type="submit" class="form-button"><?php echo __( '提交', 'wlf_form' ); ?></button><span class="warning-info" style="display: none;">111</span>
            </div>
            
        </form>
    </div>