
							</td>
							<td bgcolor="#f8f8f8"></td>
						</tr>

						<tr>
							<td><img src="<?php echo $this->themePath('img/content_bl.gif') ?>" alt="[CONTENT_BOTTOM_LEFT]" /></td>
							<td bgcolor="#f8f8f8"></td>
							<td><img src="<?php echo $this->themePath('img/content_br.gif') ?>" alt="[CONTENT_BOTTOM_RIGHT]" /></td>
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="3"></td>
				<td id="copyright">
					<?php if (Flux::config('ShowCopyright')): ?>
					<p><strong>Powered by Flux Control Panel</strong> &mdash; Copyright &copy; 2008 Matthew Harris and Nikunj Mehta.</p>
					<?php endif ?>
				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html>