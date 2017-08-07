<div id='kingkong_modal'>
	<div class='kingkong_modal_bg'></div>
	<div class='kingkong_modal_content_area'>
		<div class='kingkong_modal_content'>

			<div class="modal_content">
				<div class="modal_close"><a onclick="kingkong_popup_close();">[닫기]</a></div>
				<h2>우편번호 검색</h2>
				<table style="border:0px;">
					<tr>
						<th>지역선택 :</th>
						<td colspan="2">
							<select name="postcode_region" class="postcode_region">
								<option value="-1">지역을 선택하세요.</option>
								<option>서울특별시</option>
								<option>부산광역시</option>
								<option>대구광역시</option>
								<option>인천광역시</option>
								<option>광주광역시</option>
								<option>대전광역시</option>
								<option>울산광역시</option>
								<option>세종특별자치시</option>
								<option>강원도</option>
								<option>경기도</option>
								<option>경상남도</option>
								<option>경상북도</option>
								<option>전라남도</option>
								<option>전라북도</option>
								<option>제주특별자치도</option>
								<option>충청남도</option>
								<option>충청북도</option>
							</select>
						</td>
					</tr>
					<tr>
						<th>동,리,도로명 검색 :</th>
						<td><input type="text" name="postcode_keyword" class="postcode_keyword" onchange="get_postcode();"></td>
						<td><input type="button" value="확인" onclick="get_postcode();">
					</tr>
				</table>

				<div class="postcode_result">
					<table class="postcode_result_table">
						<thead>
							<tr>
								<th>우편번호</th>
								<th>주소</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2">검색어를 입력하시고 확인을 누르세요.</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
	
		</div>
	</div>
</div>