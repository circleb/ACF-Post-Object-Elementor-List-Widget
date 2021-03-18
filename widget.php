<?php
class Elementor_ACF_PO_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'acf_po';
	}

	public function get_title() {
		return __( 'ACF Post Object List', 'elementor-acf-po-li-extension' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Post Object', 'elementor-acf-po-li-extension' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'elementor-acf-po-li-extension' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'Title Here', 'elementor-acf-po-li-extension' ),
			]
		);

		$this->add_control(
			'field',
			[
				'label' => __( 'ACF Field Slug', 'elementor-acf-po-li-extension' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'post_object', 'elementor-acf-po-li-extension' ),
				'description' => __( 'This is the "Name" you assigned to your Post Object field in ACF.' ),
			]
		);

		$this->add_control(
			'seperator',
			[
				'label' => __( 'Seperator', 'elementor-acf-po-li-extension' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
				'placeholder' => __( 'e.g. , | <br>', 'elementor-acf-po-li-extension' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Post Object', 'elementor-acf-po-li-extension' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'elementor-acf-po-li-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::get_default_scheme(),
				],
				'selectors' => [
					'{{WRAPPER}} .acf-po-elementor-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'elementor-acf-po-li-extension' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .acf-po-elementor-title',
			]
		);

		$this->add_control(
			'posts_color',
			[
				'label' => __( 'Posts Color', 'elementor-acf-po-li-extension' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::get_default_scheme(),
				],
				'selectors' => [
					'{{WRAPPER}} .acf-po-elementor-posts' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'post_typography',
				'label' => __( 'Posts Typography', 'elementor-acf-po-li-extension' ),
				'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .acf-po-elementor-posts',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();
		$title = $settings['title'];
		$field = $settings['field'];
		$seperator = $settings['seperator'];
		$posts = get_field($field);
		$html = '';

		if( $posts ) {

			$html .= '<span class="acf-po-elementor-title">'.$title.'</span>';

			if (is_object($posts)) {

				$html .= '<a class="acf-po-elementor-posts" href="'. $posts->guid . '">' . $posts->post_title . '</a>';

			} else if (is_array($posts)) {

				$count = count($posts);

				foreach ($posts as $post) {

					$html .= '<a class="acf-po-elementor-posts" href="'. $post->guid . '">' . $post->post_title . '</a>';

				    if (--$count > 0) {

				    	$html .= $seperator;

				    }

				}

			}

		}

		echo ( $html );

	}

}
